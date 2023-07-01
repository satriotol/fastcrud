<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public static function getEvents()
    {
        $events = self::select('event')->distinct()->whereNotNull('event')->pluck('event')->toArray();
        return array_combine($events, $events);
    }
    public static function getAuditableTypes()
    {
        $auditable_types = self::select('auditable_type')->distinct()->whereNotNull('auditable_type')->pluck('auditable_type')->toArray();
        return array_combine($auditable_types, $auditable_types);
    }
}
