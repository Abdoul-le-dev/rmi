<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Commission extends Model
{
    protected $fillable = [
        'affiliate_user_id',
        'referred_user_id',
        'order_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Parrain (celui qui gagne)
    public function affiliate()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }

    // Filleul (acheteur)
    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    // Commande liée
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Commissions approuvées
    public function scopeApproved(Builder $query)
    {
        return $query->where('status', 'approved');
    }

    // En attente
    public function scopePending(Builder $query)
    {
        return $query->where('status', 'pending');
    }

    // Rejetées
    public function scopeRejected(Builder $query)
    {
        return $query->where('status', 'rejected');
    }

    // Pour un affilié donné
    public function scopeForAffiliate(Builder $query, $affiliateId)
    {
        return $query->where('affiliate_user_id', $affiliateId);
    }

    // Pour un filleul donné
    public function scopeForReferred(Builder $query, $userId)
    {
        return $query->where('referred_user_id', $userId);
    }

    // Pour une commande donnée
    public function scopeForOrder(Builder $query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function approve()
    {
        if ($this->status !== 'approved') {
            $this->update(['status' => 'approved']);

            $this->affiliate->increment('balance', $this->amount);
        }
    }

    public function reject()
    {
        if ($this->status === 'approved') {
            $this->affiliate->decrement('balance', $this->amount);
        }

        $this->update(['status' => 'rejected']);
    }


    public static function createFromOrder(Order $order): ?self
    {
        return DB::transaction(function () use ($order) {

            $referredUser = $order->user;

            if (!$referredUser) {
                return null;
            }

            // Trouver le parrain via la table affiliates
            $referrer = $referredUser->referrers()->first();

            if (!$referrer) {
                return null;
            }

            // Anti-duplication
            $existing = self::where('order_id', $order->id)
                ->where('affiliate_user_id', $referrer->id)
                ->first();

            if ($existing) {
                return $existing;
            }

            $commissionAmount = $order->total_amount * env('AFFILIATE_COMMISSION_RATE', 0.10);

            $commission = self::create([
                'affiliate_user_id' => $referrer->id,
                'referred_user_id'  => $referredUser->id,
                'order_id'          => $order->id,
                'amount'            => $commissionAmount,
                'status'            => 'approved',
            ]);

            $referrer->increment('balance', $commissionAmount);

            return $commission;
        });
    }


    public static function totalForAffiliate(int $affiliateUserId): float
    {
        return (float) self::where('affiliate_user_id', $affiliateUserId)
            ->approved() // scope approuvé
            ->sum('amount');
    }
}
