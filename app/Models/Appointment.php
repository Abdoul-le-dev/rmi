<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'full_name',
        'instructor_id',
        'subject',
        'message',
        'appointment_date',
        'duration_minutes',
        'meeting_room',
        'moderator_meeting_url',
        'participant_meeting_url',
        'participant_token',
        'moderator_token',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
        'confirmation_sent',
        'reminder_sent',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'approved_at' => 'datetime',
        'confirmation_sent' => 'boolean',
        'reminder_sent' => 'boolean',
    ];

    /**
     * Relations
     */

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'approved')
                     ->where('appointment_date', '>', now());
    }

    public function scopeNeedsReminder($query)
    {
        return $query->where('status', 'approved')
                     ->where('reminder_sent', false)
                     ->where('appointment_date', '<=', now()->addMinutes(30))
                     ->where('appointment_date', '>', now());
    }

    /**
     * Accesseurs
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            'cancelled' => 'secondary',
            default => 'dark',
        };
    }

    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm');
    }

    public function getIsUpcomingAttribute()
    {
        return $this->status === 'approved' && $this->appointment_date->isFuture();
    }

    public function getNeedsReminderAttribute()
    {
        if ($this->status !== 'approved' || $this->reminder_sent) {
            return false;
        }

        $reminderTime = now()->addMinutes(30);
        return $this->appointment_date <= $reminderTime && $this->appointment_date > now();
    }
}