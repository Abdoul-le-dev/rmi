<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LiveClassEnrollment extends Model
{
    protected $fillable = [
        'live_class_id',
        'user_id',
        'enrolled_at',
        'joined_at',
        'left_at',
        'duration_seconds',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function liveClass()
    {
        return $this->belongsTo(LiveClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsJoined()
    {
        $this->update([
            'joined_at' => now(),
        ]);
    }

    public function markAsLeft()
    {
        if ($this->joined_at) {
            $duration = now()->diffInSeconds($this->joined_at);
            $this->update([
                'left_at' => now(),
                'duration_seconds' => $this->duration_seconds + $duration,
            ]);
        }
    }
}
