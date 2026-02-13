<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'bank_name',
        'account_number',
        'account_holder_name',
        'iban',
        'swift_code',
        'mobile_operator',
        'mobile_number',
        'mobile_account_name',
        'status',
        'rejection_reason',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui demande le retrait
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'admin qui a traité la demande
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope pour filtrer les retraits en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour filtrer les retraits approuvés
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope pour filtrer les retraits rejetés
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope pour filtrer les retraits annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Vérifier si le retrait peut être annulé
     */
    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le retrait peut être traité
     */
    public function canBeProcessed()
    {
        return $this->status === 'pending';
    }

    /**
     * Obtenir le badge de statut avec couleur (Bootstrap 4)
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">En attente</span>',
            'approved' => '<span class="badge badge-primary">Approuvé</span>',
            'rejected' => '<span class="badge badge-danger">Rejeté</span>',
            'cancelled' => '<span class="badge badge-secondary">Annulé</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Inconnu</span>';
    }

    /**
     * Obtenir les informations de paiement formatées
     */
    public function getPaymentDetailsAttribute()
    {
        if ($this->payment_method === 'bank') {
            return [
                'Banque' => $this->bank_name,
                'Titulaire' => $this->account_holder_name,
                'Numéro de compte' => $this->account_number,
                'IBAN' => $this->iban,
                'Code SWIFT' => $this->swift_code,
            ];
        } else {
            return [
                'Opérateur' => $this->mobile_operator,
                'Numéro' => $this->mobile_number,
                'Nom du compte' => $this->mobile_account_name,
            ];
        }
    }
}