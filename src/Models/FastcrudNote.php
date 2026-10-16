<?php

namespace Satriotol\Fastcrud\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FastcrudNote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($note) {
            $note->uuid ??= Str::uuid()->toString();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
