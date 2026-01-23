<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LiveClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'room_name',
        'scheduled_at',
        'duration_minutes',
        'started_at',
        'ended_at',
        'is_public',
        'public_token',
        'status',
        'max_participants',
        'settings',
        'is_being_recorded',
        'auto_record'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_public' => 'boolean',
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($liveClass) {
            if (empty($liveClass->room_name)) {
                $liveClass->room_name = self::generateRoomName();
            }
            if ($liveClass->is_public && empty($liveClass->public_token)) {
                $liveClass->public_token = Str::random(32);
            }
        });
    }

    /**
     * Génère un nom de salle unique
     */
    public static function generateRoomName(): string
    {
        do {
            $roomName = 'live-' . time() . '-' . Str::random(8);
        } while (self::where('room_name', $roomName)->exists());

        return $roomName;
    }

    /**
     * Relations
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(LiveClassEnrollment::class);
    }

    public function participants()
    {
        return $this->hasMany(LiveClassParticipant::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'live_class_enrollments')
            ->withTimestamps()
            ->withPivot('enrolled_at', 'joined_at', 'left_at', 'duration_seconds');
    }

     public function recordings()
    {
        return $this->hasMany(LiveClassRecording::class);
    }

    /**
     * Scopes
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '>', now());
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeEnded($query)
    {
        return $query->where('status', 'ended');
    }

    /**
     * Accessors & Mutators
     */
    public function getEndTimeAttribute()
    {
        return $this->scheduled_at->addMinutes($this->duration_minutes);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'live';
    }

    public function getCanStartAttribute()
    {
        // Peut démarrer 15 minutes avant l'heure prévue
        return $this->status === 'scheduled' 
            && $this->scheduled_at->subMinutes(15)->isPast();
    }

    public function getPublicUrlAttribute()
    {
        if (!$this->is_public || !$this->public_token) {
            return null;
        }
        return route('live-class.join-public', $this->public_token);
    }

    /**
     * Méthodes métier
     */
    public function start()
    {
        $this->update([
            'status' => 'live',
            'started_at' => now(),
        ]);

        return $this;
    }

    public function end()
    {
        $this->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return $this;
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);

        return $this;
    }

    public function isEnrolled(User $user): bool
    {
        return $this->enrollments()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function enroll(User $user)
    {
        if ($this->isEnrolled($user)) {
            return false;
        }

        return $this->enrollments()->create([
            'user_id' => $user->id,
            'enrolled_at' => now(),
        ]);
    }

    public function unenroll(User $user)
    {
        return $this->enrollments()
            ->where('user_id', $user->id)
            ->delete();
    }

    public function getEnrolledCount(): int
    {
        return $this->enrollments()->count();
    }

    public function canEnroll(): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        if ($this->max_participants && $this->getEnrolledCount() >= $this->max_participants) {
            return false;
        }

        return true;
    }
}
