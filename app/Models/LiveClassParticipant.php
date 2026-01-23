<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LiveClassParticipant extends Model
{
    protected $fillable = [
        'live_class_id',
        'user_id',
        'name',
        'email',
        'is_moderator',
        'jwt_token',
        'token_expires_at',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'is_moderator' => 'boolean',
        'token_expires_at' => 'datetime',
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

    public function isTokenValid(): bool
    {
        if (!$this->jwt_token || !$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isFuture();
    }
}
