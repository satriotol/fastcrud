<?php

namespace Satriotol\Fastcrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Support\Str;

class Config extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'configs';

    protected $fillable = ["description", "type", "config_value"];

    public static function types()
    {
        return [
            "string",
            "int",
            "boolean",
            "url"
        ];
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
