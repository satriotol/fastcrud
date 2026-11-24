<?php

namespace Satriotol\Fastcrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Config extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'configs';

    protected $fillable = ["config_key", "description", "type", "config_value"];

    protected $appends = ['model_config', 'asset_url'];

    public function getModelConfigAttribute()
    {
        return "Config::where('uuid', '$this->uuid')->first()->config_value";
    }

    public static function types()
    {
        return [
            "string",
            "int",
            "boolean",
            "url",
            'text',
            'image',
            'file'
        ];
    }

    public static function fileTypes(): array
    {
        return ['image', 'file'];
    }

    public static function isFileTypeValue(?string $type): bool
    {
        return in_array($type, self::fileTypes(), true);
    }

    public function isFileType(): bool
    {
        return in_array($this->type, self::fileTypes(), true);
    }

    public static function disk(): \Illuminate\Filesystem\FilesystemAdapter
    {
        return Storage::disk(config('fastcrud.asset_disk', 'public'));
    }

    public function getAssetUrlAttribute(): ?string
    {
        return $this->isFileType() && $this->config_value
            ? self::disk()->url($this->config_value)
            : null;
    }

    public static function remember(string $key, $default = null)
    {
        return Cache::rememberForever("fastcrud.config.{$key}", fn () => static::where('config_key', $key)->value('config_value') ?? $default);
    }

    public static function flushCache(): void
    {
        static::whereNotNull('config_key')->pluck('config_key')->each(fn ($key) => Cache::forget("fastcrud.config.{$key}"));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
