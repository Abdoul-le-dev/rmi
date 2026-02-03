<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassEnrollment;
use App\Models\LiveClassParticipant;
use App\Services\JitsiService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    // public function index()
    // {
    //     $authUser = Auth::user();
    //     $liveClasses = LiveClass::where('instructor_id', Auth::id())
    //         ->orderBy('scheduled_at', 'desc')
    //         ->paginate(10);

    //     return view('live-classes.index', compact('liveClasses', 'authUser'));
    // }

    /**
     * Formulaire de création
     */
    // public function create()
    // {
    //     $authUser = Auth::user();
    //     return view('liveclass.create', compact('authUser'));
    // }

    /**
     * Créer un nouveau live class
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'is_public' => 'required|boolean',

            'max_participants' => 'nullable|integer|min:1|max:1000',
            'live_cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload de l'image si présente
        $coverPath = null;
        if ($request->hasFile('live_cover')) {
            $coverPath = $request->file('live_cover')->store(
                'live-covers',
                's3' //  s3 en prod
            );
        }

        $liveClass = LiveClass::create([
            'instructor_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_public' => $validated['is_public'],
            'auto_record' => false,
            // 'status' => 'scheduled',
            'max_participants' => $validated['max_participants']
                ?? config('jitsi.live_class.default_max_participants'),
            'live_cover' => $coverPath,
        ]);

        $liveClass->load('instructor:id,full_name,email');

        return response()->json([
            'success' => true,
            'message' => 'Live class créé avec succès',
            'data' => $liveClass
        ], 201);
       
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
        $authUser = Auth::user();
        return view('liveclass.show', compact('liveClass', 'enrolledCount', 'isEnrolled', 'authUser'));
    }

    

    public function update(Request $request, LiveClass $liveClass)
    {
        $this->authorize('update', $liveClass);


        if (in_array($liveClass->status, ['live', 'ended'])) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier un live en cours ou terminé'
            ], 422);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'sometimes|required|date|after:now',
            'duration_minutes' => 'sometimes|required|integer|min:15|max:480',
            'max_participants' => 'nullable|integer|min:1',
            'is_public' => 'boolean',
            'auto_record' => 'boolean',
            'live_cover' => 'nullable|image|max:2048'
        ]);

        // Génération ou suppression du token public
        if ($request->has('is_public')) {
            if ($request->boolean('is_public') && !$liveClass->public_token) {
                $validated['public_token'] = Str::random(32);
            } elseif (!$request->boolean('is_public')) {
                $validated['public_token'] = null;
            }
        }

        
        if ($request->hasFile('live_cover')) {
            
            if ($liveClass->live_cover) {
                Storage::disk('s3')->delete($liveClass->live_cover); //s3 en prod
            }

            $path = $request->file('live_cover')->store('live-covers', 's3'); //s3
            $validated['live_cover'] = $path;
        }

        $liveClass->update($validated);
        $liveClass->load('instructor:id,full_name,email');

        return response()->json([
            'success' => true,
            'message' => 'Live class mis à jour avec succès',
            'data' => $liveClass
        ]);
    }

    public function destroy(LiveClass $liveClass)
    {
        // Vérifier que l'utilisateur est le créateur
        if ($liveClass->instructor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Ne peut pas supprimer un live en cours
        if ($liveClass->status === 'live') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer un live en cours'
            ], 422);
        }

        // Supprimer l'image de couverture
        if ($liveClass->live_cover) {
            Storage::disk('s3')->delete( $liveClass->live_cover); //s3
        }

        $liveClass->delete();

        return response()->json([
            'success' => true,
            'message' => 'Live class supprimé avec succès'
        ]);
    }


   

    public function enroll(LiveClass $liveClass)
    {
        if (!$liveClass->canEnroll()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de s\'inscrire à ce live class.'
            ], 422);
        }

        if ($liveClass->isEnrolled(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Vous êtes déjà inscrit.'
            ], 422);
        }

        $liveClass->enroll(Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie!'
        ], 200);
    }

    public function unenroll(LiveClass $liveClass)
    {
        if (!$liveClass->isEnrolled(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas inscrit à ce live class.'
            ], 422);
        }

        $liveClass->unenroll(Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Désinscription réussie.'
        ], 200);
    }


    public function start(LiveClass $liveClass)
    {
        $this->authorize('start', $liveClass);

        if ($liveClass->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Ce live class ne peut pas être démarré.'
            ], 422);
        }

        if (!$liveClass->can_start) {
            $minutesBeforeStart = $liveClass->scheduled_at->diffInMinutes(now());
            return response()->json([
                'success' => false,
                'message' => "Vous pourrez démarrer dans {$minutesBeforeStart} minutes."
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Marquer comme live
            $liveClass->start();

           
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

            // Retourner l'URL de la salle Jitsi
            return response()->json([
                'success' => true,
                'message' => 'Live démarré avec succès',
                'url' => $tokenData['url']
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du démarrage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function end(LiveClass $liveClass)
    {
        $this->authorize('end', $liveClass);

        if ($liveClass->status !== 'live') {
            return response()->json([
                'success' => false,
                'message' => 'Ce live class n\'est pas actif.'
            ], 422);
        }

        $liveClass->end();

        return response()->json([
            'success' => true,
            'message' => 'Live class terminé.'
        ], 200);
    }

    /**
     * Helper pour générer l'URL Jitsi avec token
     */
    private function generateJitsiUrl($roomName, $token)
    {
        return "https://" . config('jitsi.domain') . "/{$roomName}?jwt={$token}#config.autoRecord=true";
    }

    /**
     * Rejoindre un live class
     */
    public function join(LiveClass $liveClass)
    {
        // Vérifier que le live est actif
        if ($liveClass->status !== 'live') {
            return response()->json([
                'success' => false,
                'message' => 'Ce live class n\'est pas encore démarré.'
            ], 422);
        }

        $isOwner = $liveClass->instructor_id === Auth::id();

        // Si c'est le propriétaire
        if ($isOwner) {
            // Récupérer le participant (token déjà généré au démarrage)
            $participant = LiveClassParticipant::where('live_class_id', $liveClass->id)
                ->where('user_id', Auth::id())
                ->where('is_moderator', true)
                ->first();

            if ($participant && $participant->jwt_token) {
                // Régénérer l'URL avec le token existant
                $url = $this->generateJitsiUrl($liveClass->room_name, $participant->jwt_token);

                return response()->json([
                    'success' => true,
                    'message' => 'Rejoindre le live',
                    'url' => $url
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Token introuvable. Veuillez redémarrer le live.'
            ], 422);
        }

        // Si ce n'est pas le propriétaire
        // Vérifier l'inscription si le live n'est pas public
        if (!$liveClass->is_public && !$liveClass->isEnrolled(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être inscrit pour rejoindre ce live.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Vérifier si un token existe déjà pour ce participant
            $participant = LiveClassParticipant::where('live_class_id', $liveClass->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($participant && $participant->jwt_token && $participant->token_expires_at > now()) {
                // Token encore valide, régénérer l'URL
                $url = $this->generateJitsiUrl($liveClass->room_name, $participant->jwt_token);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Rejoindre le live',
                    'url' => $url
                ], 200);
            }

            // Générer un nouveau token JWT pour l'apprenant
            $tokenData = $this->jitsiService->generateStudentToken(
                $liveClass->room_name,
                Auth::user()->full_name ?? "eleve",
                Auth::user()->email,
                $liveClass->duration_minutes
            );

            // Enregistrer ou mettre à jour le participant
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

            // Retourner l'URL de la salle Jitsi
            return response()->json([
                'success' => true,
                'message' => 'Rejoindre le live',
                'url' => $tokenData['url']
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la connexion: ' . $e->getMessage()
            ], 500);
        }
    }

    public function joinPublic(Request $request, string $token)
    {
        $liveClass = LiveClass::where('public_token', $token)
            ->where('is_public', true)
            ->firstOrFail();

        // Pour les requêtes GET - Afficher la page
        if ($request->isMethod('get')) {
            return view('vip.lives.public', compact('liveClass'));
        }

        // Pour les requêtes POST - Rejoindre le live
        if ($liveClass->status !== 'live') {
            return response()->json([
                'success' => false,
                'message' => 'Ce live n\'est pas encore démarré.'
            ], 422);
        }

        // Si l'utilisateur est connecté, utiliser la fonction join existante
        if (Auth::check()) {
            return $this->join($liveClass);
        }

        // Pour les invités non connectés
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

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'url' => $tokenData['url']
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
   





    public function apiIndex(Request $request)
    {
        $user = $request->user();

        $query = LiveClass::query()
            ->with('instructor:id,full_name,email')
            ->withCount(['participants', 'enrollments']);

        // Scopes existants
        if ($request->filled('status')) {
            match ($request->status) {
                'scheduled' => $query->upcoming(),
                'live' => $query->live(),
                'ended' => $query->ended(),
                default => $query->where('status', $request->status),
            };
        }

        if ($request->has('is_public')) {
            $query->where('is_public', $request->boolean('is_public'));
        }

        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $query->orderBy(
            $request->get('sort_by', 'scheduled_at'),
            $request->get('sort_order', 'desc')
        );

        $liveClasses = $request->has('per_page')
            ? $query->paginate($request->per_page)
            : $query->get();

        // 🔥 Injection CONTEXTUELLE (sans casser le model)
        if ($user) {
            $liveClasses->each(function ($live) use ($user) {
                $live->is_enrolled = $live->isEnrolled($user);
            });
        }

        return response()->json([
            'success' => true,
            'data' => $liveClasses
        ]);
    }


    public function apiShow(LiveClass $liveClass)
    {
        $liveClass->load(['instructor:id,full_name,email', 'participants']);

        return response()->json([
            'success' => true,
            'data' => $liveClass
        ]);
    }



    public function index_admin(Request $request)
    {
        // Calculer les statistiques
        $totalSessions = LiveClass::count();
        $LiveClasss = LiveClass::where('status', 'pending')->count();
        $scheduledSessions = LiveClass::where('status', 'scheduled')->count();
        $completedSessions = LiveClass::where('status', 'ended')->count();

        // Récupérer tous les instructeurs pour le filtre
        $instructors = User::whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        })
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();


        // Query de base
        $query = LiveClass::with('instructor');

        // Filtre par instructeur
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par visibilité
        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public);
        }

        // Filtre par nom de salle
        if ($request->filled('room_name')) {
            $query->where('room_name', 'like', '%' . $request->room_name . '%');
        }

        // Filtre par date de début
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        // Filtre par date de fin
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }



        // Recherche textuelle (titre et description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Tri par défaut (les plus récentes en premier)
        $query->orderBy('scheduled_at', 'desc');

        // Pagination
        $sessions = $query->paginate(15)->withQueryString();

        return view('admin.live.index', compact(
            'sessions',
            'instructors',
            'totalSessions',

            'scheduledSessions',
            'completedSessions'
        ));
    }


    public function edit_admin(string $id)
    {
        $session = LiveClass::findOrFail($id);

        // Récupérer tous les instructeurs pour le formulaire
        $instructors = User::whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        })
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();

        return view('admin.live.edit', compact('session', 'instructors'));
    }

    public function update_admin(Request $request, string $id)
    {
        $session = LiveClass::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'room_name' => 'required|string|max:100',
            'scheduled_at' => 'required|date|after_or_equal:now',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'is_public' => 'required|boolean',
            'max_participants' => 'nullable|integer|min:1',
            'auto_record' => 'nullable|boolean',
            'live_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'instructor_id.required' => 'L\'instructeur est obligatoire.',
            'instructor_id.exists' => 'L\'instructeur sélectionné n\'existe pas.',
            'title.required' => 'Le titre est obligatoire.',
            'status.required' => 'Le statut est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'room_name.required' => 'Le nom de la salle est obligatoire.',
            'scheduled_at.required' => 'La date de planification est obligatoire.',
            'scheduled_at.after_or_equal' => 'La date doit être dans le futur.',
            'duration_minutes.required' => 'La durée est obligatoire.',
            'duration_minutes.min' => 'La durée doit être d\'au moins 1 minute.',
            'duration_minutes.max' => 'La durée ne peut pas dépasser 480 minutes (8 heures).',
            'is_public.required' => 'Veuillez préciser si la session est publique.',
            'live_cover.image' => 'Le fichier doit être une image.',
            'live_cover.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'live_cover.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->hasFile('live_cover')) {
            // Supprimer l'ancienne image
            if ($session->live_cover) {
                Storage::disk('public')->delete($session->live_cover); //s3
            }

            $path = $request->file('live_cover')->store('live-covers', 'public'); //s3
            $validated['live_cover'] = $path;
        }

        // Conversion de auto_record
        $validated['auto_record'] = $request->has('auto_record') ? true : false;

        // Mise à jour des données
        $session->update($validated);

        return redirect()->route('sessions.index')
            ->with('success', 'Session mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_admin(string $id)
    {
        try {
            $session = LiveClass::findOrFail($id);


            if ($session->status === 'live') {
                return redirect()->route('sessions.index')
                    ->with('error', 'Impossible de supprimer une session en direct.');
            }



            if ($session->live_cover) {
                Storage::disk('public')->delete($session->live_cover); //s3
            }


            $session->delete();

            return redirect()->route('sessions.index')
                ->with('success', 'Session supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('sessions.index')
                ->with('error', 'Erreur lors de la suppression de la session : ' . $e->getMessage());
        }
    }
}
