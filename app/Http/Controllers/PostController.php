<?php

namespace App\Http\Controllers;

use App\Events\PollVoted;
use App\Events\PostDeleted;
use App\Events\PostShared;
// Events
use App\Models\PollOption;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\ForumTopic;
use App\Models\Forum;

class PostController extends Controller
{
    /**
     * Récupérer les posts (pour le load initial du WebSocket)
     */
    public function fetchs(Request $request)
    {
        $posts = Post::with([
            'user',
            'media',
            'poll.options',
            'comments' => function ($query) {
                $query->with('user')->whereNull('parent_id')->latest()->limit(3);
            },
        ])
            ->where('status', 'published')
            ->latest()
            ->limit($request->input('limit', 10))
            ->get();

        // Enrichir chaque post avec toutes les données nécessaires
        $posts->each(function ($post) {
            // Compteurs
            $post->likes_count = $post->likes_count ?? 0;
            $post->comments_count = $post->comments_count ?? 0;
            $post->shares_count = $post->shares_count ?? 0;

            // Informations utilisateur enrichies
            if ($post->user) {
                $post->user->role = $post->user->role ?? 'membre';
                $post->user->avatar = $post->user->avatar ?? null;
            }

            // Plaque et montant (système de trophées)
            $latestTrophe = \App\Models\Trophe::where('user_id', $post->user_id)
                ->where('status', 'validated')
                ->latest()
                ->first();

            $post->plaque = $latestTrophe ? $this->determinePlaque($latestTrophe->montant_généré) : 'none';
            $post->montant = $latestTrophe ? (float) $latestTrophe->montant_généré : 0;

            // Type de média (image ou video)
            if ($post->media) {
                $post->media->each(function ($media) {
                    $extension = pathinfo($media->path, PATHINFO_EXTENSION);
                    $videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
                    $media->type = in_array(strtolower($extension), $videoExtensions) ? 'video' : 'image';
                });
            }

            if ($post->comments) {
                $post->comments->each(function ($comment) {
                    if ($comment->user) {
                        $comment->user->avatar = $comment->user->avatar ?? null;
                    }
                });
            }
        });

        return response()->json([
            'success' => true,
            'posts' => $posts,
            'current_user' => auth()->user(),
        ]);
    }

    public function fetchss()
    {
        $id=11;
        try {

            $forum = ForumTopic::where('forum_id', $id)->latest()
                ->limit(10)
                ->get();  
            $posts = Post::with(['user', 'media', 'poll.options'])
                ->where('status', 'published')->where('forum_id',$id)
                ->latest()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'posts' => $posts,
                'forum' =>  $forum,
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

    public function fetch(Request $request)
    {
        
        $validated = $request->validate([
            'channel_id' => 'required' 
        ]);
        try {

            $forum = ForumTopic::where('forum_id', $validated['channel_id'])->latest()
                ->limit(10)
                ->get();  
            $posts = Post::with(['user', 'media', 'poll.options'])
                ->where('status', 'published')//->where('forum_id',$validated['channel_id'] )
                //->latest()
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'posts' => $posts,
                'forum' =>  '',
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
        $forums = Forum::with(['topics'])->where('status', 'active')->get();

        $posts = Post::with(['user', 'media', 'poll.options', 'comments.user'])
        ->where('status', 'published')
        ->latest()
        ->paginate(20);

      

        return view('vip.canal', compact("forums","posts"));
    }

    /**
     * Déterminer la plaque selon le montant généré
     */
    private function determinePlaque($montant)
    {
        $montant = (float) $montant;

        if ($montant >= 10000) {
            return 'diamond'; // 💎
        } elseif ($montant >= 5000) {
            return 'gold'; // 🥇
        } elseif ($montant >= 1000) {
            return 'silver'; // 🥈
        } elseif ($montant >= 100) {
            return 'bronze'; // 🥉
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

        $option = PollOption::findOrFail($validated['option_id']);
        $option->increment('votes');

        // 🔥 Déclencher l'événement WebSocket
        event(new PollVoted($validated['poll_id'], $validated['option_id']));

        return response()->json([
            'success' => true,
            'votes' => $option->votes,
        ]);
    }

    /**
     * Supprimer un post
     */
    public function delete(Request $request)
{
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Non authentifié'
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
            'error' => $e->getMessage()
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
}
