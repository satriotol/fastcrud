<?php

namespace Satriotol\Fastcrud\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FastcrudRegistration extends Model
{
    use HasFactory;

    protected $table = 'fastcrud_registrations';

    protected $fillable = [
        'name',
        'email',
        'password',
        'note',
        'status',
        'reject_reason',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'password' => 'hashed',
        'processed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Token link pendaftaran, diturunkan dari APP_KEY.
     * Stabil per instalasi, tidak perlu disimpan di database.
     *
     * ponytail: token tidak bisa dirotasi tanpa mengganti APP_KEY.
     * Kalau link bocor dan perlu diganti, pindahkan token ke satu row tabel `configs`.
     */
    public static function linkToken(): string
    {
        return substr(hash('sha256', config('app.key') . 'fastcrud-register'), 0, 32);
    }

    public static function linkUrl(): string
    {
        return route('fastcrud_registration.form', static::linkToken());
    }
}
