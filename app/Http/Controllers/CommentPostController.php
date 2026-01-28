<?php

namespace App\Http\Controllers;

use App\Models\CommentPost;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentPostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comment_posts,id',
        ]);

        $comment = CommentPost::create([
            'post_id' => $validated['post_id'],
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        // Incrémenter le compteur
        Post::where('id', $validated['post_id'])->increment('comments_count');

        // Charger les relations
        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ], 201);
    }

    public function index($postId)
    {
        $comments = CommentPost::with(['user', 'replies.user'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:comment_posts,id',
        ]);

        $comment = CommentPost::findOrFail($validated['comment_id']);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $postId = $comment->post_id;
        $comment->delete();

        Post::where('id', $postId)->decrement('comments_count');

        return response()->json([
            'success' => true,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:comment_posts,id',
            'content' => 'required|string|max:2000',

        ]);

        $comment = CommentPost::where('id', $validated['comment_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $comment->update([
            'content' => $validated['content'],
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }

    public function store_share(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        // Incrément atomique (safe concurrence)
        Post::where('id', $validated['post_id'])
            ->increment('shares_count');

        return response()->json([
            'success' => true,
        ]);
    }
}
