<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_type',
        'recipients',
        'subject',
        'content',
        'excel_file_path',
        'total_recipients',
        'sent_count',
        'failed_count',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui a envoyé l'email
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les destinataires
     */
    public function emailRecipients(): HasMany
    {
        return $this->hasMany(EmailRecipient::class);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les emails envoyés récemment
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Vérifier si l'envoi est terminé
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifier si l'envoi a échoué
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Vérifier si l'envoi est en cours
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Calculer le taux de réussite
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_recipients === 0) {
            return 0;
        }

        return round(($this->sent_count / $this->total_recipients) * 100, 2);
    }
}