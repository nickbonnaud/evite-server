<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model {
    use HasFactory, HasUuids;

    protected $casts = [
        'notification_sent' => 'boolean',
        'viewed' => 'boolean',
        'will_attend' => 'boolean'
    ];

    protected $hidden = ['user_id', 'created_at', 'updated_at'];
    protected $guarded = ['id', 'short_id'];
    
    public static function boot(): void {
        parent::boot();
        static::creating(fn (Rsvp $rsvp) => $rsvp->short_id = unique_random('rsvps', 'short_id', 4));
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
