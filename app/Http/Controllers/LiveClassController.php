<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassEnrollment;
use App\Models\LiveClassParticipant;
use App\Services\JitsiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LiveClassController extends Controller
{
    protected JitsiService $jitsiService;

    public function __construct(JitsiService $jitsiService)
    {
        $this->jitsiService = $jitsiService;
    }

    /**
     * Liste des live classes (instructeur)
     */
    public function index()
    {
        $authUser=Auth::user();
        $liveClasses = LiveClass::where('instructor_id', Auth::id())
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('live-classes.index', compact('liveClasses','authUser'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $authUser=Auth::user();
        return view('liveclass.create',compact('authUser'));
    }

    /**
     * Créer un nouveau live class
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480', // 15min à 8h
            'is_public' => 'required|boolean',
            'auto_record' => 'required|boolean',
            'max_participants' => 'nullable|integer|min:1|max:1000',
        ]);

        $liveClass = LiveClass::create([
            'instructor_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_public' => $validated['is_public'],
            'auto_record' => $validated['auto_record'],
            'max_participants' => $validated['max_participants'] ?? config('jitsi.live_class.default_max_participants'),
            'status' => 'scheduled',
        ]);

        return redirect()
            ->route('live-classes.show', $liveClass)
            ->with('success', 'Live class créé avec succès!');
    }

    /**
     * Afficher un live class
     */
    public function show(LiveClass $liveClass)
    {
        $this->authorize('view', $liveClass);

        $liveClass->load(['instructor', 'enrollments.user']);
        
        $enrolledCount = $liveClass->getEnrolledCount();
        $isEnrolled = Auth::check() && $liveClass->isEnrolled(Auth::user());
        $authUser=Auth::user();
        return view('liveclass.show', compact('liveClass', 'enrolledCount', 'isEnrolled','authUser'));
    }

    /**
     * Modifier un live class
     */
    public function update(Request $request, LiveClass $liveClass)
    {
        $this->authorize('update', $liveClass);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'is_public' => 'required|boolean',
            'max_participants' => 'nullable|integer|min:1|max:1000',
        ]);

        $liveClass->update($validated);

        return redirect()
            ->route('live-classes.show', $liveClass)
            ->with('success', 'Live class mis à jour!');
    }

    /**
     * S'inscrire à un live class
     */
    public function enroll(LiveClass $liveClass)
    {
        if (!$liveClass->canEnroll()) {
            return back()->with('error', 'Impossible de s\'inscrire à ce live class.');
        }

        if ($liveClass->isEnrolled(Auth::user())) {
            return back()->with('info', 'Vous êtes déjà inscrit.');
        }

        $liveClass->enroll(Auth::user());

        return back()->with('success', 'Inscription réussie!');
    }

    /**
     * Se désinscrire d'un live class
     */
    public function unenroll(LiveClass $liveClass)
    {
        if (!$liveClass->isEnrolled(Auth::user())) {
            return back()->with('error', 'Vous n\'êtes pas inscrit à ce live class.');
        }

        $liveClass->unenroll(Auth::user());

        return back()->with('success', 'Désinscription réussie.');
    }

    /**
     * DÉMARRER le live class (instructeur)
     */
    public function start(LiveClass $liveClass)
    {
        $this->authorize('start', $liveClass);

        if ($liveClass->status !== 'scheduled') {
            return back()->with('error', 'Ce live class ne peut pas être démarré.');
        }

        if (!$liveClass->can_start) {
            $minutesBeforeStart = $liveClass->scheduled_at->diffInMinutes(now());
            return back()->with('error', "Vous pourrez démarrer dans {$minutesBeforeStart} minutes.");
        }

        DB::beginTransaction();
        try {
            // Marquer comme live
            $liveClass->start();

            // Générer le token JWT pour l'instructeur
            $tokenData = $this->jitsiService->generateInstructorToken(
                $liveClass->room_name,
                Auth::user()->full_name ?? 'nom',
                Auth::user()->email,
                $liveClass->duration_minutes
            );

            // Enregistrer le participant (instructeur)
            $participant = LiveClassParticipant::create([
                'live_class_id' => $liveClass->id,
                'user_id' => Auth::id(),
                'name' => Auth::user()->full_name,
                'email' => Auth::user()->email,
                'is_moderator' => true,
                'jwt_token' => $tokenData['token'],
                'token_expires_at' => $tokenData['expires_at'],
            ]);

            DB::commit();

            // Rediriger vers la salle Jitsi
            return redirect($tokenData['url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du démarrage: ' . $e->getMessage());
        }
    }

    /**
     * REJOINDRE le live class (apprenant)
     */
    public function join(LiveClass $liveClass)
    {
        // Vérifier que le live est actif
        if ($liveClass->status !== 'live') {
            return back()->with('error', 'Ce live class n\'est pas encore démarré.');
        }

        // Vérifier l'inscription si le live n'est pas public
        if (!$liveClass->is_public && !$liveClass->isEnrolled(Auth::user())) {
            return back()->with('error', 'Vous devez être inscrit pour rejoindre ce live.');
        }

        DB::beginTransaction();
        try {
            // Générer le token JWT pour l'apprenant
            $tokenData = $this->jitsiService->generateStudentToken(
                $liveClass->room_name,
                Auth::user()->full_name ?? "eleve",
                Auth::user()->email,
                $liveClass->duration_minutes
            );

            // Enregistrer le participant
            $participant = LiveClassParticipant::updateOrCreate(
                [
                    'live_class_id' => $liveClass->id,
                    'user_id' => Auth::id(),
                ],
                [
                    'name' => Auth::user()->full_name ?? "nom",
                    'email' => Auth::user()->email,
                    'is_moderator' => false,
                    'jwt_token' => $tokenData['token'],
                    'token_expires_at' => $tokenData['expires_at'],
                ]
            );

            // Marquer l'inscription comme "joined"
            if ($liveClass->isEnrolled(Auth::user())) {
                $enrollment = LiveClassEnrollment::where('live_class_id', $liveClass->id)
                    ->where('user_id', Auth::id())
                    ->first();
                $enrollment?->markAsJoined();
            }

            DB::commit();

            // Rediriger vers la salle Jitsi
            return redirect($tokenData['url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la connexion: ' . $e->getMessage());
        }
    }

    /**
     * REJOINDRE en tant qu'invité (lien public)
     */
    public function joinPublic(Request $request, string $token)
    {
        $liveClass = LiveClass::where('public_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        if ($liveClass->status !== 'live') {
            return view('live-classes.not-started', compact('liveClass'));
        }

        // Si l'utilisateur est connecté, utiliser ses infos
        if (Auth::check()) {
            return $this->join($liveClass);
        }

        // Formulaire pour les invités non connectés
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
            ]);

            DB::beginTransaction();
            try {
                // Générer le token JWT pour l'invité
                $tokenData = $this->jitsiService->generateGuestToken(
                    $liveClass->room_name,
                    $validated['name'],
                    $validated['email'],
                    $liveClass->duration_minutes
                );

                // Enregistrer le participant externe
                LiveClassParticipant::create([
                    'live_class_id' => $liveClass->id,
                    'user_id' => null, // Invité externe
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'is_moderator' => false,
                    'jwt_token' => $tokenData['token'],
                    'token_expires_at' => $tokenData['expires_at'],
                ]);

                DB::commit();

                return redirect($tokenData['url']);

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Erreur: ' . $e->getMessage());
            }
        }

        // Afficher le formulaire pour les invités
        return view('live-classes.join-public', compact('liveClass'));
    }

    /**
     * TERMINER le live class
     */
    public function end(LiveClass $liveClass)
    {
        $this->authorize('end', $liveClass);

        if ($liveClass->status !== 'live') {
            return back()->with('error', 'Ce live class n\'est pas actif.');
        }

        $liveClass->end();

        return redirect()
            ->route('live-classes.show', $liveClass)
            ->with('success', 'Live class terminé.');
    }

    /**
     * Dashboard étudiant - Mes live classes
     */
    public function studentDashboard()
    {
        $upcomingClasses = LiveClass::whereHas('enrollments', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $liveClasses = LiveClass::whereHas('enrollments', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', 'live')
            ->get();

        $pastClasses = LiveClass::whereHas('enrollments', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('status', 'ended')
            ->orderBy('ended_at', 'desc')
            ->limit(5)
            ->get();
             $authUser=Auth::user();

        return view('liveclass.student-dashboard', compact('upcomingClasses', 'liveClasses', 'pastClasses','authUser'));
    }
}