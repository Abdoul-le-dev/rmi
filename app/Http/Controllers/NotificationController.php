<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendEmailRequest;
use App\Jobs\SendBulkEmailJob;
use App\Models\EmailRecipient;
use App\Models\SentEmail;
use App\Models\User;
use App\Services\ExcelEmailExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class NotificationController extends Controller
{
     protected $emailExtractor;

    public function __construct(ExcelEmailExtractor $emailExtractor)
    {
        $this->emailExtractor = $emailExtractor;
    }

    /**
     * Afficher le formulaire d'envoi de notifications
     */
    public function index()
    {
        return view('admin.notification.index');
    }

    /**
     * Envoyer les emails
     */
    public function sendMail(SendEmailRequest $request)
    {
        try {
            DB::beginTransaction();

            $recipients = $this->collectRecipients($request);

            if (empty($recipients)) {
                return back()->with('error', 'Aucun destinataire valide trouvé.');
            }

            // Sauvegarder le fichier Excel si fourni
            $excelFilePath = null;
            if ($request->hasFile('excel_file')) {
                $excelFilePath = $request->file('excel_file')->store('emails/attachments', 'private');
            }

            // Créer l'enregistrement de l'email envoyé
            $sentEmail = SentEmail::create([
                'user_id' => Auth::id(),
                'recipient_type' => $request->send_to_users ? 'users' : 'custom',
                'recipients' => $recipients,
                'subject' => $request->subject,
                'content' => $request->content,
                'excel_file_path' => $excelFilePath,
                'total_recipients' => count($recipients),
                'status' => 'pending',
            ]);

            // Créer les enregistrements des destinataires
            foreach ($recipients as $email) {
                EmailRecipient::create([
                    'sent_email_id' => $sentEmail->id,
                    'email' => $email,
                    'status' => 'pending',
                ]);
            }

            // Dispatcher le job d'envoi
            SendBulkEmailJob::dispatch($sentEmail);

            DB::commit();

            return back()->with('success', 
                "Email ajouté à la file d'attente avec succès. " . 
                count($recipients) . " destinataire(s) seront contactés."
            );

        } catch (Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Collecter tous les destinataires
     */
    private function collectRecipients(SendEmailRequest $request): array
    {
        $recipients = [];

        // 1. Si "envoyer à tous les utilisateurs" est coché
        if ($request->send_to_users) {
            $recipients = User::whereNotNull('email')
                ->pluck('email')
                ->toArray();
        }

        // 2. Ajouter les emails personnalisés
        if (!empty($request->custom_emails)) {
            $customEmails = $this->parseCustomEmails($request->custom_emails);
            $recipients = array_merge($recipients, $customEmails);
        }

        // 3. Ajouter les emails du fichier Excel
        if ($request->hasFile('excel_file')) {
            try {
                $excelEmails = $this->emailExtractor->extractEmails($request->file('excel_file'));
                $recipients = array_merge($recipients, $excelEmails);
            } catch (Exception $e) {
                throw new Exception('Erreur lors de la lecture du fichier Excel : ' . $e->getMessage());
            }
        }

        // Nettoyer et valider les emails
        return $this->emailExtractor->validateEmails($recipients);
    }

    /**
     * Parser les emails personnalisés
     */
    private function parseCustomEmails(string $emails): array
    {
        $emailList = preg_split('/[,;\n\r]+/', $emails);
        return array_filter(array_map('trim', $emailList));
    }

    /**
     * Afficher l'historique des emails envoyés
     */
    public function history(Request $request)
    {
        $query = SentEmail::with(['user', 'emailRecipients'])
            ->orderBy('created_at', 'desc');

        // Filtrer par statut si fourni
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtrer par date
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sentEmails = $query->paginate(20);

        return view('admin.notifications.history', compact('sentEmails'));
    }

    /**
     * Afficher les détails d'un email envoyé
     */
    public function show(SentEmail $sentEmail)
    {
        $sentEmail->load(['user', 'emailRecipients']);

        return view('admin.notifications.show', compact('sentEmail'));
    }

    /**
     * Supprimer un email de l'historique
     */
    public function destroy(SentEmail $sentEmail)
    {
        try {
            // Supprimer le fichier Excel si présent
            if ($sentEmail->excel_file_path) {
                Storage::disk('private')->delete($sentEmail->excel_file_path);
            }

            $sentEmail->delete();

            return back()->with('success', 'Email supprimé avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Renvoyer un email échoué
     */
    public function retry(SentEmail $sentEmail)
    {
        if ($sentEmail->status !== 'failed') {
            return back()->with('error', 'Seuls les emails échoués peuvent être renvoyés.');
        }

        try {
            // Réinitialiser les destinataires échoués
            $sentEmail->emailRecipients()
                ->where('status', 'failed')
                ->update(['status' => 'pending']);

            // Réinitialiser le statut de l'email
            $sentEmail->update([
                'status' => 'pending',
                'error_message' => null,
            ]);

            // Redispatcher le job
            SendBulkEmailJob::dispatch($sentEmail);

            return back()->with('success', 'Email ajouté à nouveau à la file d\'attente.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors du renvoi : ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques
     */
    public function statistics()
    {
        $stats = [
            'total_sent' => SentEmail::sum('sent_count'),
            'total_failed' => SentEmail::sum('failed_count'),
            'total_campaigns' => SentEmail::count(),
            'pending_campaigns' => SentEmail::where('status', 'pending')->count(),
            'processing_campaigns' => SentEmail::where('status', 'processing')->count(),
            'recent_campaigns' => SentEmail::recent(30)->count(),
        ];

        return view('admin.notifications.statistics', compact('stats'));
    }


    /**
     * Prévisualiser les emails d'un fichier Excel
     */
    public function previewExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $emails = $this->emailExtractor->previewEmails($request->file('excel_file'), 20);
            
            return response()->json([
                'success' => true,
                'count' => count($emails),
                'emails' => array_slice($emails, 0, 10), // Afficher les 10 premiers
                'message' => count($emails) . ' email(s) valide(s) trouvé(s)',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Valider un fichier Excel
     */
    public function validateExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $count = $this->emailExtractor->countEmails($request->file('excel_file'));
            
            return response()->json([
                'success' => true,
                'valid' => $count > 0,
                'count' => $count,
                'message' => $count > 0 
                    ? "$count email(s) valide(s) détecté(s)" 
                    : "Aucun email valide trouvé dans le fichier",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}