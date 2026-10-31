<?php

namespace Satriotol\Fastcrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $table = 'api_keys';

    protected $fillable = ["key", "is_active", "last_used_at", "note", "allow_web", "allow_mobile", "allow_flutter", "allow_desktop", "allow_backend", "allow_localhost", "allowed_routes"];

    protected $casts = ['allowed_routes' => 'array', 'last_used_at' => 'datetime'];

    public function logs()
    {
        return $this->hasMany(ApiKeyLog::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
