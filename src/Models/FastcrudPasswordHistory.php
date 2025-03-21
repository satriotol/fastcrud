<?php

namespace Satriotol\Fastcrud\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FastcrudPasswordHistory extends Model
{
    protected $fillable = ['user_id', 'password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
