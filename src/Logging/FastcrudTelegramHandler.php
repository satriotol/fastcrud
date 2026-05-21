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

        // ── Detail Exception (jika ada) ──────────────────────
        if ($exception instanceof Throwable) {
            $text .= "🔴 *Exception:* `" . get_class($exception) . "`\n";
            $text .= "📄 *File :*\n`" . $exception->getFile() . "`\n";
            $text .= "📍 *Line :* `" . $exception->getLine() . "`\n\n";

            // Stack trace — tampilkan 10 frame teratas dengan path lengkap
            $trace = $exception->getTrace();
            if (!empty($trace)) {
                $text .= "🔖 *Stack Trace (10 frame teratas):*\n```\n";
                foreach (array_slice($trace, 0, 10) as $i => $frame) {
                    $file  = $frame['file']     ?? '[internal]';
                    $line  = $frame['line']     ?? '?';
                    $class = $frame['class']    ?? '';
                    $type  = $frame['type']     ?? '';
                    $func  = $frame['function'] ?? '?';

                    $caller = $class ? "{$class}{$type}{$func}()" : "{$func}()";
                    $text  .= "#" . str_pad($i, 2, '0', STR_PAD_LEFT)
                            . " {$file}:{$line}\n"
                            . "   → {$caller}\n";
                }
                $text .= "```\n\n";
            }

            // Pesan exception asli (jika berbeda dari $message)
            if ($exception->getMessage() !== $message) {
                $text .= "📝 *Exception Message:*\n`"
                       . $this->escape(substr($exception->getMessage(), 0, 300))
                       . "`\n\n";
            }

        } else {
            // Tidak ada exception — tampilkan context biasa jika ada
            if (!empty($context)) {
                $encoded = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $text .= "📦 *Context:*\n```json\n" . substr($encoded, 0, 800) . "\n```\n\n";
            }
        }

        // ── Informasi Request HTTP ───────────────────────────
        if (app()->runningInConsole()) {
            $text .= "⚙️ *Sumber:* `Console / Artisan`\n";
            $argv = $_SERVER['argv'] ?? [];
            if (!empty($argv)) {
                $text .= "📜 *Command:* `" . implode(' ', $argv) . "`\n";
            }
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

        // ── Kirim ke Telegram ────────────────────────────────
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        // Telegram max 4096 karakter — potong jika perlu
        if (mb_strlen($text) > self::TELEGRAM_LIMIT) {
            $text = mb_substr($text, 0, self::TELEGRAM_LIMIT - 50)
                  . "\n\n⚠️ _[Pesan dipotong karena terlalu panjang]_";
        }

        try {
            Http::timeout(5)->post($url, [
                'chat_id'    => $this->chatId,
                'text'       => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            // Abaikan agar aplikasi tidak crash jika Telegram gagal
        }
    }

    /**
     * Escape karakter spesial Markdown Telegram agar tidak merusak format.
     */
    private function escape(string $text): string
    {
        return str_replace(['`', '*', '_', '[', ']'], ['\\`', '\\*', '\\_', '\\[', '\\]'], $text);
    }
}
