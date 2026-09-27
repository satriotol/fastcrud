<?php

namespace Satriotol\Fastcrud\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AuditsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected Collection $audits;

    public function __construct(Collection $audits)
    {
        $this->audits = $audits;
    }

    public function collection(): Collection
    {
        return $this->audits;
    }

    public function title(): string
    {
        return 'Audit Log';
    }

    public function headings(): array
    {
        return [
            'Log ID',
            'Waktu',
            'Event',
            'User ID',
            'Nama User',
            'Email',
            'Model',
            'ID Entitas',
            'Perubahan',
            'IP Address',
            'URL',
            'Tags',
        ];
    }

    public function map($audit): array
    {
        return [
            $audit->id,
            $audit->created_at?->format('Y-m-d H:i:s'),
            $audit->event,
            $audit->user_id ?? '-',
            $audit->user->name ?? 'Sistem',
            $audit->user->email ?? '-',
            class_basename($audit->auditable_type),
            $audit->auditable_id,
            self::summarizeChanges($audit->old_values ?? [], $audit->new_values ?? []),
            $audit->ip_address ?? '-',
            $audit->url ?? '-',
            $audit->tags ?? '-',
        ];
    }

    /**
     * Ringkas perubahan menjadi teks "kolom: lama → baru" per baris,
     * hanya untuk kolom yang nilainya benar-benar berubah.
     */
    public static function summarizeChanges(array $old, array $new, int $valueLimit = 80): string
    {
        $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
        $lines = [];

        foreach ($keys as $key) {
            $before = $old[$key] ?? null;
            $after = $new[$key] ?? null;
            if ($before === $after) {
                continue;
            }
            $lines[] = sprintf(
                '%s: %s → %s',
                $key,
                Str::limit(self::formatValue($before), $valueLimit),
                Str::limit(self::formatValue($after), $valueLimit)
            );
        }

        return $lines ? implode("\n", $lines) : '-';
    }

    public static function formatValue($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }
        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return (string) $value;
    }
}
