<?php

namespace App\Models;

use App\Notifications\RsvpAdded;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Rsvp extends Model {
    use HasFactory, HasUuids, Notifiable;

    protected $casts = [
        'notification_sent' => 'boolean',
        'viewed' => 'boolean',
        'will_attend' => 'boolean'
    ];

    protected $hidden = ['user_id', 'created_at', 'updated_at', 'short_id'];
    protected $guarded = ['id', 'short_id'];
    
    public static function boot(): void {
        parent::boot();
        static::creating(fn (Rsvp $rsvp) => $rsvp->short_id = unique_random('rsvps', 'short_id', 4));
        // static::created(fn (Rsvp $rsvp) => $rsvp->notify(new RsvpAdded()));
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function routeNotificationForVonage(Notification $notification): string {
        return $this->number;
    }
}
