<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     * Afficher la page des retraits de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('web.default.panel.withdrawal.index', compact('user', 'withdrawals'));
    }

    /**
     * Créer une nouvelle demande de retrait
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation
        $rules = [
            'amount' => 'required|numeric|min:50|max:' . $user->balance,
            'payment_method' => 'required|in:bank,mobile_money',
        ];

        // Validation conditionnelle selon la méthode de paiement
        if ($request->payment_method === 'bank') {
            $rules['bank_name'] = 'required|string|max:255';
            $rules['account_holder_name'] = 'required|string|max:255';
            $rules['account_number'] = 'required|string|max:255';
            $rules['iban'] = 'nullable|string|max:255';
            $rules['swift_code'] = 'nullable|string|max:255';
        } else {
            $rules['mobile_operator'] = 'required|string|max:255';
            $rules['mobile_number'] = 'required|string|max:255';
            $rules['mobile_account_name'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules, [
            'amount.required' => 'Le montant est requis.',
            'amount.min' => 'Le montant minimum est de 50 dollars.',
            'amount.max' => 'Solde insuffisant.',
            'payment_method.required' => 'La méthode de paiement est requise.',
        ]);

        DB::beginTransaction();
        try {
            // Vérifier le solde disponible
            if ($user->balance < $validated['amount']) {
                return back()->with('error', 'Solde insuffisant pour effectuer ce retrait.');
            }

            // Débiter la balance de l'utilisateur
            $user->balance -= $validated['amount'];
            $user->save();

            // Créer la demande de retrait
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder_name' => $request->account_holder_name,
                'iban' => $request->iban,
                'swift_code' => $request->swift_code,
                'mobile_operator' => $request->mobile_operator,
                'mobile_number' => $request->mobile_number,
                'mobile_account_name' => $request->mobile_account_name,
                'status' => 'pending',
            ]);

            DB::commit();

            return back()->with('success', 'Votre demande de retrait a été créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la création de la demande.');
        }
    }

    /**
     * Annuler une demande de retrait
     */
    public function cancel($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        
        // Vérifier que c'est bien l'utilisateur propriétaire
        if ($withdrawal->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifier que le retrait peut être annulé
        if (!$withdrawal->canBeCancelled()) {
            return back()->with('error', 'Ce retrait ne peut plus être annulé.');
        }

        DB::beginTransaction();
        try {
            // Rembourser le montant à l'utilisateur
            $user = Auth::user();
            $user->balance += $withdrawal->amount;
            $user->save();

            // Mettre à jour le statut
            $withdrawal->status = 'cancelled';
            $withdrawal->save();

            DB::commit();

            return back()->with('success', 'Votre demande de retrait a été annulée et le montant a été remboursé.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'annulation.');
        }
    }

    /**
     * Afficher les détails d'un retrait
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        
        // Vérifier que c'est bien l'utilisateur propriétaire
        if ($withdrawal->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('web.default.panel.withdrawal.show', compact('withdrawal'));
    }
}