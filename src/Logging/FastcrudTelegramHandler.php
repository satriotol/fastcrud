<?php

namespace Satriotol\Fastcrud\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use Throwable;

class FastcrudTelegramHandler extends AbstractProcessingHandler
{
    protected $token;
    protected $chatId;

    // Batas karakter Telegram per pesan
    const TELEGRAM_LIMIT = 4096;

    public function __construct($token, $chatId, $level = Logger::ERROR, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->token = $token;
        $this->chatId = $chatId;
    }

    protected function write(LogRecord $record): void
    {
        $levelName = $record->level->getName();
        $message   = $record->message;
        $context   = $record->context;
        $datetime  = $record->datetime;

        // Abaikan log duplikat yang ditulis ulang oleh Laravel Debugbar
        // (mis. "Debugbar exception: SQLSTATE..." untuk error yang sama)
        if (str_starts_with($message, 'Debugbar exception:')) {
            return;
        }

        /** @var Throwable|null $exception */
        $exception = $context['exception'] ?? null;

        $text  = "🚨 *LARAVEL ERROR REPORT* 🚨\n";
        $text .= str_repeat('─', 30) . "\n\n";

        // ── Informasi Dasar ──────────────────────────────────
        $text .= "📌 *App :* `" . env('APP_NAME', 'Laravel') . "`\n";
        $text .= "🌍 *Env :* `" . env('APP_ENV', 'unknown') . "`\n";
        $text .= "⚡ *Level :* `{$levelName}`\n";
        $text .= "🕐 *Waktu :* `" . ($datetime instanceof \DateTimeInterface
                ? $datetime->format('Y-m-d H:i:s')
                : (string) $datetime) . "`\n\n";

        // ── Pesan Error ──────────────────────────────────────
        $text .= "💬 *Pesan:*\n`" . $this->escape($message) . "`\n\n";

        // ── Informasi Request HTTP ───────────────────────────
        if (app()->runningInConsole()) {
            $text .= "⚙️ *Sumber:* `Console / Artisan`\n";
            $argv = $_SERVER['argv'] ?? [];
            if (!empty($argv)) {
                $text .= "📜 *Command:* `" . implode(' ', $argv) . "`\n";
            }
            $text .= "\n";
        } else {
            try {
                $request = request();
                $text .= "🌐 *URL :* `" . $request->fullUrl() . "`\n";
                $text .= "📡 *Method :* `" . $request->method() . "`\n";
                $text .= "🖥️ *IP :* `" . $request->ip() . "`\n";
                $ua = $request->userAgent();
                if ($ua) {
                    $text .= "🔎 *User-Agent :* `" . substr($ua, 0, 120) . "`\n";
                }

                // ── Input Request (selalu ditampilkan) ───────────
                $input = $request->except([
                    'password',
                    'password_confirmation',
                    'current_password',
                    '_token',
                ]);
                $encodedInput = empty($input)
                    ? '(kosong)'
                    : json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $text .= "📥 *Input :*\n```json\n" . substr($encodedInput, 0, 800) . "\n```\n";

                $text .= "\n";
            } catch (\Throwable) {
                // request() belum tersedia (bootstrap error)
            }
        }

        // ── Informasi User ───────────────────────────────────
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $text .= "👤 *User :* `" . ($user->name ?? $user->email ?? $user->id ?? 'unknown') . "`\n";
                $text .= "🆔 *User ID :* `" . $user->id . "`\n\n";
            }
        } catch (\Throwable) {
            // Auth belum siap
        }

        // ── Detail Exception (jika ada) ──────────────────────
        if ($exception instanceof Throwable) {
            $text .= "🔴 *Exception:* `" . get_class($exception) . "`\n";
            $text .= "📄 *File :*\n`" . $exception->getFile() . "`\n";
            $text .= "📍 *Line :* `" . $exception->getLine() . "`\n\n";

            $trace = $exception->getTrace();
            if (!empty($trace)) {
                $text .= "🔖 *Stack Trace (5 frame teratas):*\n```\n";
                foreach (array_slice($trace, 0, 5) as $i => $frame) {
                    $file  = $frame['file']     ?? '[internal]';
                    $line  = $frame['line']     ?? '?';
                    $class = $frame['class']    ?? '';
                    $type  = $frame['type']     ?? '';
                    $func  = $frame['function'] ?? '?';

                    $caller = $class ? "{$class}{$type}{$func}()" : "{$func}()";
                    $text  .= "#" . str_pad($i, 2, '0', STR_PAD_LEFT)
                            . " {$file}:{$line} → {$caller}\n";
                }
                $text .= "```\n\n";
            }

            if ($exception->getMessage() !== $message) {
                $text .= "📝 *Exception Message:*\n`"
                       . $this->escape(substr($exception->getMessage(), 0, 300))
                       . "`\n\n";
            }

        } else {
            if (!empty($context)) {
                $encoded = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $text .= "📦 *Context:*\n```json\n" . substr($encoded, 0, 800) . "\n```\n\n";
            }
        }

        // ── Kirim ke Telegram ────────────────────────────────
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        // Potong jika terlalu panjang
        $text = $this->truncate($text, "\n\n⚠️ _[Pesan dipotong karena terlalu panjang]_");

        // Gunakan fungsi helper untuk mengirim laporan error
        $this->sendToTelegram($url, $text, 'Markdown');

        // Kirim pesan kedua: prompt analisis AI
        if ($exception instanceof Throwable) {
            $this->sendAnalysisPrompt($exception, $message);
        }
    }

    protected function sendAnalysisPrompt(Throwable $exception, string $message): void
    {
        $file       = $exception->getFile();
        $line       = $exception->getLine();
        $trace      = $this->formatTraceText($exception);
        $sourceCode = $this->getSourceCode($file, $line);

        $prompt  = "Anda adalah senior Laravel engineer.\n\n";
        $prompt .= "Analisis error berikut:\n\n";
        $prompt .= "ERROR:\n{$message}\n\n";
        $prompt .= "FILE:\n{$file}\n\n";
        $prompt .= "LINE:\n{$line}\n\n";
        $prompt .= "STACK TRACE:\n{$trace}\n\n";
        $prompt .= "SOURCE CODE:\n{$sourceCode}\n\n";
        $prompt .= "Berikan output:\n\n";
        $prompt .= "1. Root Cause\n";
        $prompt .= "2. Confidence (0-100%)\n";
        $prompt .= "3. Kemungkinan lokasi masalah\n";
        $prompt .= "4. Langkah perbaikan\n";
        $prompt .= "5. Contoh kode perbaikan\n";
        $prompt .= "6. Risiko jika tidak diperbaiki\n\n";
        $prompt .= "Jawab dalam bahasa Indonesia.";

        $prompt = $this->truncate($prompt, "\n\n⚠️ [Prompt dipotong karena terlalu panjang]");
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        // Gunakan fungsi helper tanpa Parse Mode untuk prompt (lebih aman)
        $this->sendToTelegram($url, $prompt);
    }

    /**
     * Helper untuk memproses pengiriman dengan fitur Auto-Fallback
     */
    private function sendToTelegram(string $url, string $text, ?string $parseMode = null): void
    {
        try {
            $payload = [
                'chat_id' => $this->chatId,
                'text'    => $text,
            ];

            if ($parseMode) {
                $payload['parse_mode'] = $parseMode;
            }

            $response = Http::timeout(5)->post($url, $payload);

            // Jika respons gagal (biasanya HTTP 400 karena Markdown terpotong) dan kita memakai parse_mode
            if (!$response->successful() && $parseMode) {
                // Hapus parse_mode, kirim ulang sebagai PLAIN TEXT agar tetap masuk
                unset($payload['parse_mode']);
                Http::timeout(5)->post($url, $payload);
            }
        } catch (\Exception $e) {
            // Abaikan error koneksi (Timeout/DNS) agar aplikasi utama tidak crash
        }
    }

    private function formatTraceText(Throwable $exception): string
    {
        $trace  = $exception->getTrace();
        $result = '';
        foreach (array_slice($trace, 0, 8) as $i => $frame) {
            $file   = $frame['file']     ?? '[internal]';
            $line   = $frame['line']     ?? '?';
            $class  = $frame['class']    ?? '';
            $type   = $frame['type']     ?? '';
            $func   = $frame['function'] ?? '?';
            $caller = $class ? "{$class}{$type}{$func}()" : "{$func}()";
            $result .= "#" . str_pad($i, 2, '0', STR_PAD_LEFT)
                     . " {$file}:{$line} → {$caller}\n";
        }
        return rtrim($result);
    }

    private function getSourceCode(string $file, int $errorLine, int $context = 5): string
    {
        if (!is_readable($file)) {
            return '(file tidak dapat dibaca)';
        }

        $lines = file($file);
        if ($lines === false) {
            return '(gagal membaca file)';
        }

        $start  = max(0, $errorLine - $context - 1);
        $end    = min(count($lines) - 1, $errorLine + $context - 1);
        $result = '';

        for ($i = $start; $i <= $end; $i++) {
            $lineNum = $i + 1;
            $marker  = ($lineNum === $errorLine) ? '>>> ' : '    ';
            $result .= $marker . str_pad($lineNum, 4) . ': ' . rtrim($lines[$i]) . "\n";
        }

        return rtrim($result);
    }

    private function truncate(string $text, string $notice): string
    {
        if (mb_strlen($text) <= self::TELEGRAM_LIMIT) {
            return $text;
        }

        $keep = self::TELEGRAM_LIMIT - mb_strlen($notice);

        return mb_substr($text, 0, max(0, $keep)) . $notice;
    }

    private function escape(string $text): string
    {
        return str_replace(['`', '*', '_', '[', ']'], ['\\`', '\\*', '\\_', '\\[', '\\]'], $text);
    }
}