<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     * Afficher la liste des demandes de retrait
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user', 'processor'])
            ->orderBy('created_at', 'desc');

        // Filtrage par statut
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Recherche par utilisateur
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $withdrawals = $query->paginate(15);

        // Statistiques
        $stats = [
            'total_pending' => Withdrawal::pending()->count(),
            'total_pending_amount' => Withdrawal::pending()->sum('amount'),
            'total_approved' => Withdrawal::approved()->count(),
            'total_approved_amount' => Withdrawal::approved()->sum('amount'),
            'total_rejected' => Withdrawal::rejected()->count(),
        ];

        return view('admin.withdrawal.index', compact('withdrawals', 'stats'));
    }

    /**
     * Afficher les détails d'une demande de retrait
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with(['user', 'processor'])->findOrFail($id);
        return view('admin.withdrawal.show', compact('withdrawal'));
    }

    /**
     * Approuver une demande de retrait
     */
    public function approve($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        // Vérifier que le retrait peut être traité
        if (!$withdrawal->canBeProcessed()) {
            return back()->with('error', 'Cette demande ne peut plus être traitée.');
        }

        DB::beginTransaction();
        try {
            $withdrawal->status = 'approved';
            $withdrawal->processed_at = now();
            $withdrawal->processed_by = Auth::id();
            $withdrawal->save();

            DB::commit();

            return back()->with('success', 'La demande de retrait a été approuvée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'approbation.');
        }
    }

    /**
     * Rejeter une demande de retrait
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Le motif de rejet est requis.',
            'rejection_reason.max' => 'Le motif ne peut pas dépasser 1000 caractères.',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        // Vérifier que le retrait peut être traité
        if (!$withdrawal->canBeProcessed()) {
            return back()->with('error', 'Cette demande ne peut plus être traitée.');
        }

        DB::beginTransaction();
        try {
            // Rembourser le montant à l'utilisateur
            $user = $withdrawal->user;
            $user->balance += $withdrawal->amount;
            $user->save();

            // Mettre à jour le retrait
            $withdrawal->status = 'rejected';
            $withdrawal->rejection_reason = $request->rejection_reason;
            $withdrawal->processed_at = now();
            $withdrawal->processed_by = Auth::id();
            $withdrawal->save();

            DB::commit();

            return back()->with('success', 'La demande de retrait a été rejetée et le montant a été remboursé.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du rejet.');
        }
    }

    /**
     * Export des retraits (bonus)
     */
    public function export(Request $request)
    {
        $query = Withdrawal::with('user');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->get();

        $filename = 'withdrawals_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($withdrawals) {
            $file = fopen('php://output', 'w');
            
            // En-têtes du CSV
            fputcsv($file, [
                'ID',
                'Utilisateur',
                'Email',
                'Montant',
                'Méthode',
                'Statut',
                'Date de demande',
                'Date de traitement',
            ]);

            // Données
            foreach ($withdrawals as $withdrawal) {
                fputcsv($file, [
                    $withdrawal->id,
                    $withdrawal->user->name,
                    $withdrawal->user->email,
                    $withdrawal->amount,
                    $withdrawal->payment_method,
                    $withdrawal->status,
                    $withdrawal->created_at->format('Y-m-d H:i:s'),
                    $withdrawal->processed_at ? $withdrawal->processed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}