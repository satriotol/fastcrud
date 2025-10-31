<?php

namespace Satriotol\Fastcrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Support\Str;
use App\Models\User;

class FastcrudErrorLog extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'fastcrud_error_logs';

    protected $fillable = [
        "uuid",
        "message",
        "trace",
        "error_code",
        "url",
        "method",
        "input",
        "user_id",
        "ip_address",
        "user_agent",
        "environment",
        "level",
        "status"
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
