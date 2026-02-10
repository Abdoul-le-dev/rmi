<?php

namespace App\Http\Controllers;

use App\Events\PostDeleted;
use App\Events\PostShared;
// Events
use App\Helpers\S3Helper;
use App\Models\ForumTopic;
use App\Models\PollOption;
use App\Models\Post;
use App\Models\Translation\ForumTranslation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//Auth::loginUsingId(22422);

class PostController extends Controller
{
    /**
     * Récupérer les posts (pour le load initial du WebSocket)
     */
    public function fetch(Request $request)
    {

        $validated = $request->validate([
            'channel_id' => 'required',
        ]);
        try {

            $forum = ForumTopic::where('forum_id', $validated['channel_id'])->latest()
                ->limit(10)
                ->get();
            $posts = Post::with(['user', 'media', 'poll.options'])
                ->where('status', 'published')// ->where('forum_id',$validated['channel_id'] )
                // ->latest()
                ->limit(50)
                ->get();

            // 🔥 AJOUT ICI
            $posts->each(function ($post) {
                $post->media->each(function ($media) {
                    $media->url = S3Helper::getTemporaryUrl($media->path, 60);
                });
            });

            return response()->json([
                'success' => true,
                'posts' => $posts,
                'forum' => '',
                'current_user' => auth()->user(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function canal_index()
    {
        $forums = ForumTranslation::with(['forum', 'topics'])->get();

        $posts = Post::with(['user', 'media', 'poll.options', 'comments.user'])
            ->where('status', 'published')
            ->latest()
            ->paginate(20);

        $user = auth()->user();

        $validatedTrophes = $user->trophes()
            ->where('status', 'validated')
            ->get();

        $montantTotal = $validatedTrophes->sum('montant_genere');

        $percent = ($montantTotal / 1000) + 1;

        $userData = [
            'user_id' => $user->id,
            'user_name' => $user->full_name,
            'user_status' => $this->formatRole($user->role_name),
            'montant_total' => $montantTotal,
            'montant_restant' => $this->montantRestant($montantTotal),
            'plaque' => $this->resolvePlaque($montantTotal),
            'percent' => $percent,
            'nombre_etudiants' => $this->nombreEtudiants(),
            'nombre_posts' => $this->nombrePosts(),
            'students_online' => 0,
            'link_image' => '',
            'description' => '',
        ];

        return view('vip.canal', compact('forums', 'posts', 'userData'));
    }

    /**
     * Déterminer la plaque selon le montant généré
     */
    private function determinePlaque($montant)
    {
        $montant = (float) $montant;

        if ($montant >= 10000) {
            return 'diamond 💎'; //
        } elseif ($montant >= 5000) {
            return 'gold 🥇'; //
        } elseif ($montant >= 1000) {
            return 'silver🥈'; //
        } elseif ($montant >= 100) {
            return 'bronze🥉'; //
        }

        return 'none'; // ⭐
    }

    /**
     * Voter sur un sondage
     */
    public function vote(Request $request)
    {
        $validated = $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $option = PollOption::where('poll_id', $validated['poll_id'])
            ->where('id', $validated['option_id'])
            ->firstOrFail();

        $option->increment('votes');

        $poll = $option->poll()->with('options')->first();

        $totalVotes = $poll->options->sum('votes');

        return response()->json([
            'success' => true,
            'poll' => [
                'id' => $poll->id,
                'total_votes' => $totalVotes,
                'options' => $poll->options->map(fn ($opt) => [
                    'id' => $opt->id,
                    'votes_count' => $opt->votes,
                ]),
            ],
        ]);
    }

    /**
     * Supprimer un post
     */
    public function delete(Request $request)
    {
        if (! auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        $validated = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        $post = Post::findOrFail($validated['post_id']);

        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $postId = $post->id;

        $post->delete();

        // Event sécurisé
        try {
            event(new PostDeleted($postId));
        } catch (\Throwable $e) {
            logger()->error('Broadcast PostDeleted failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Partager un post
     */
    public function share(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($validated['post_id']);

        // Incrémenter le compteur shares_count
        $post->increment('shares_count');

        // 🔥 Déclencher l'événement WebSocket
        event(new PostShared($post->id));

        return response()->json([
            'success' => true,
            'shares_count' => $post->shares_count,
        ]);
    }

    private function getMediaType($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'webm'];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        }
        if (in_array(strtolower($extension), $videoExtensions)) {
            return 'video';
        }

        return 'unknown';
    }

    public function vote_(Request $request)
    {
        $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_id' => 'required|exists:poll_options,id',
        ]);

        // Vérifier que l'option appartient bien au poll
        $option = PollOption::where('id', $request->option_id)
            ->where('poll_id', $request->poll_id)
            ->firstOrFail();

        // Incrémenter les votes
        $option->increment('votes');

        // Récupérer le total de votes pour ce poll
        $totalVotes = PollOption::where('poll_id', $request->poll_id)->sum('votes');

        return response()->json([
            'success' => true,
            'votes' => $option->votes,
            'total_votes' => $totalVotes,
            'message' => 'Vote enregistré avec succès',
        ]);
    }

    private function formatRole(string $role): string
    {
        return $role === 'user' ? 'Etudiant' : ucfirst($role);
    }

    private function resolvePlaque(float $montant): string
    {
        return match (true) {
            $montant >= 100000 => 'diamond 💎',
            $montant >= 20000 => 'gold 🥇',
            $montant >= 10000 => 'silver 🥈',
            $montant >= 5000 => 'bronze 🥉',
            default => 'none',
        };
    }

    private function montantRestant(float $montant): float
    {
        return max(0, (100000 - $montant) / 1000);
    }

    private function nombreEtudiants(): float
    {
        return (User::count() - 10) / 1000;
    }

    private function nombrePosts(): int
    {
        return Post::count() + ForumTopic::count();
    }
}
