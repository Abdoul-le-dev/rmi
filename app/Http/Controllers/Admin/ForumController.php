<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumTopicPost;
use App\Models\Group;
use App\Models\PollOption;
use App\Models\Post;
use App\Models\Role;
use App\Models\Translation\ForumTranslation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
Auth::loginUsingId(22422); 

class ForumController extends Controller
{
    public function index(Request $request)
    {
        removeContentLocale();

        $this->authorize('admin_forum_list');

        $subForums = $request->get('subForums');

        $forums = Forum::where(function ($query) use ($subForums) {
            if (! empty($subForums)) {
                $query->where('parent_id', $subForums);
            } else {
                $query->whereNull('parent_id');
            }
        })
            ->with([
                'subForums' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->orderBy('id', 'desc')
            ->paginate(10);

        foreach ($forums as $forum) {
            $forumIds = Forum::where('parent_id', $forum->id)->pluck('id')->toArray();
            $forumIds[] = $forum->id;

            $topicsIds = ForumTopic::whereIn('forum_id', $forumIds)->pluck('id')->toArray();

            $forum->topics_count = count($topicsIds);
            $forum->posts_count = ForumTopicPost::whereIn('topic_id', $topicsIds)->count();
        }

        $totalForums = Forum::query()->whereDoesntHave('subForums')->count();
        $totalTopics = ForumTopic::query()->count();
        $postsCount = ForumTopicPost::query()->count();
        $membersCount = ForumTopicPost::select(DB::raw('count(distinct user_id) as count'))->first()->count;

        $data = [
            'pageTitle' => trans('update.forums'),
            'forums' => $forums,
            'totalForums' => $totalForums,
            'totalTopics' => $totalTopics,
            'postsCount' => $postsCount,
            'membersCount' => $membersCount,
        ];

        return view('admin.forums.lists', $data);
    }

    public function create()
    {
        $this->authorize('admin_forum_create');

        $userGroups = Group::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = Role::all();

        $data = [
            'pageTitle' => trans('update.new_forum'),
            'userGroups' => $userGroups,
            'roles' => $roles,
        ];

        return view('admin.forums.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_forum_create');

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'description' => 'required',
            'icon' => 'required',
            'role_id' => 'nullable|exists:roles,id',
            'group_id' => 'nullable|exists:groups,id',
            'status' => 'in:active,disabled',
        ]);
        $data = $request->all();

        $forum = Forum::create([
            'slug' => Forum::makeSlug($data['title']),
            'icon' => $data['icon'],
            'group_id' => $data['group_id'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'status' => $data['status'],
            'close' => (! empty($data['close']) and $data['close'] == 1),
        ]);

        ForumTranslation::updateOrCreate([
            'forum_id' => $forum->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $hasSubForum = (! empty($request->get('has_sub')) and $request->get('has_sub') == 'on');
        $this->setSubForum($forum, $request->get('sub_forums'), $hasSubForum, $data['locale']);

        removeContentLocale();

        return redirect(getAdminPanelUrl().'/forums');
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_forum_edit');

        $forum = Forum::findOrFail($id);
        $subForums = Forum::where('parent_id', $forum->id)
            ->orderBy('order', 'asc')
            ->get();

        $userGroups = Group::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = Role::all();

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $forum->getTable(), $forum->id);

        $data = [
            'pageTitle' => trans('admin/main.edit'),
            'forum' => $forum,
            'subForums' => $subForums,
            'userGroups' => $userGroups,
            'roles' => $roles,
        ];

        return view('admin.forums.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_forum_edit');

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'description' => 'required',
            'icon' => 'required',
            'group_id' => 'nullable|exists:groups,id',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'in:active,disabled',
        ]);

        $data = $request->all();

        $forum = Forum::findOrFail($id);
        $forum->update([
            'icon' => $data['icon'],
            'group_id' => $data['group_id'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'status' => $data['status'],
            'close' => (! empty($data['close']) and $data['close'] == 1),
        ]);

        ForumTranslation::updateOrCreate([
            'forum_id' => $forum->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $hasSubForums = (! empty($request->get('has_sub')) and $request->get('has_sub') == 'on');
        $this->setSubForum($forum, $request->get('sub_forums'), $hasSubForums, $data['locale']);

        removeContentLocale();

        return redirect(getAdminPanelUrl().'/forums');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_forum_delete');

        $forum = Forum::where('id', $id)->first();

        if (! empty($forum)) {
            Forum::where('parent_id', $forum->id)
                ->delete();

            $forum->delete();
        }

        return redirect(getAdminPanelUrl().'/forums');
    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        $option = $request->get('option', null);

        $query = Forum::select('id')
            ->whereTranslationLike('title', "%$term%");

        /*if (!empty($option)) {

        }*/

        $forums = $query->get();

        return response()->json($forums, 200);
    }

    public function searchTopics(Request $request)
    {
        $term = $request->get('term');

        $option = $request->get('option', null);

        $query = ForumTopic::select('id', 'title')
            ->where('title', 'like', "%$term%");

        $topics = $query->get();

        return response()->json($topics, 200);
    }

    public function setSubForum(Forum $forum, $subForums, $hasSubForums, $locale)
    {
        $order = 1;
        $oldIds = [];

        if ($hasSubForums and ! empty($subForums) and count($subForums)) {

            foreach ($subForums as $key => $subForum) {
                $check = Forum::where('id', $key)->first();

                if (is_numeric($key)) {
                    $oldIds[] = $key;
                }

                if (! empty($subForum['title'])) {
                    if (! empty($check)) {
                        $check->update([
                            'order' => $order,
                            'icon' => $subForum['icon'],
                            'group_id' => $subForum['group_id'] ?? null,
                            'role_id' => $subForum['role_id'] ?? null,
                            'status' => $subForum['status'],
                            'close' => $forum->close || ((! empty($subForum['close']) and $subForum['close'] == 1)),
                        ]);

                        ForumTranslation::updateOrCreate([
                            'forum_id' => $check->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $subForum['title'],
                            'description' => $subForum['description'],
                        ]);
                    } else {
                        $new = Forum::create([
                            'slug' => Forum::makeSlug($subForum['title']),
                            'parent_id' => $forum->id,
                            'order' => $order,
                            'icon' => $subForum['icon'],
                            'group_id' => $subForum['group_id'] ?? null,
                            'role_id' => $subForum['role_id'] ?? null,
                            'status' => $subForum['status'],
                            'close' => $forum->close || ((! empty($subForum['close']) and $subForum['close'] == 1)),
                        ]);

                        ForumTranslation::updateOrCreate([
                            'forum_id' => $new->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $subForum['title'],
                            'description' => $subForum['description'],
                        ]);

                        $oldIds[] = $new->id;
                    }

                    $order += 1;
                }
            }
        }

        Forum::where('parent_id', $forum->id)
            ->whereNotIn('id', $oldIds)
            ->delete();

        return true;
    }

    // teams abdoulledev
    public function event_index()
    {

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

        return view('vip.event', compact('userData'));
    }

    public function new_index(Request $request)
    {
        $post = null;

        try {
            DB::transaction(function () use ($request, &$post) {

                /** ---------------- VALIDATION ---------------- */
                $request->validate([
                    'contents' => 'nullable|string|max:5000',
                    'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,webm|max:204800',
                    'scheduled_at_date' => 'nullable|date',
                    'scheduled_at_time' => 'nullable|date_format:H:i',
                ]);

                /** ---------------- PLANIFICATION ---------------- */
                $scheduledAt = null;
                if ($request->filled('scheduled_at_date') && $request->filled('scheduled_at_time')) {
                    $scheduledAt = $request->scheduled_at_date.' '.$request->scheduled_at_time.':00';
                }

                $status = $scheduledAt ? 'scheduled' : 'published';

                /** ---------------- TYPE DU POST ---------------- */
                $type = 'text';
                $pollData = null;

                if ($request->hasFile('media')) {
                    $type = 'media';
                }

                if ($request->filled('poll')) {
                    $poll = is_string($request->poll)
                        ? json_decode($request->poll, true)
                        : $request->poll;

                    if (
                        isset($poll['question'], $poll['options']) &&
                        trim($poll['question']) !== '' &&
                        is_array($poll['options']) &&
                        count(array_filter($poll['options'])) >= 2
                    ) {
                        $type = 'sondage';
                        $pollData = [
                            'question' => trim($poll['question']),
                            'options' => array_filter(array_map('trim', $poll['options'])),
                        ];
                    }
                }

                /** ---------------- CRÉATION DU POST ---------------- */
                $post = Post::create([
                    'user_id' => auth()->id(),
                    'forum_id' => $request->forum_id,
                    'content' => $request->contents,
                    'type' => $type,
                    'status' => $status,
                    'scheduled_at' => $scheduledAt,
                ]);

                /** ---------------- MÉDIAS ---------------- */
                if ($request->hasFile('media')) {
                    $files = $request->file('media');
                    $files = is_array($files) ? $files : [$files];

                    foreach ($files as $file) {
                        if (! $file || ! $file->isValid()) {
                            continue;
                        }

                        $path = $file->store('posts', 'public');

                        $post->media()->create([
                            'path' => $path,
                        ]);
                    }
                }

                /** ---------------- SONDAGE ---------------- */
                if ($pollData) {
                    $poll = $post->poll()->create([
                        'question' => $pollData['question'],
                    ]);

                    foreach ($pollData['options'] as $option) {
                        $poll->options()->create([
                            'option' => $option,
                        ]);
                    }
                }
            });

            /** ---------------- RESPONSE ---------------- */
            $post->load(['user', 'media', 'poll.options']);

            return response()->json([
                'success' => true,
                'message' => 'Post créé avec succès',
                'post' => $post,
            ], 201);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du post',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }


    public function home_index()
    {

        $user = auth()->user();

        $validatedTrophes = $user->trophes()
            ->where('status', 'validated')
            ->get();

        $montantTotal = 1000050 ;//$validatedTrophes->sum('montant_genere');

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
            ->limit(10)
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

        return view('vip.app', compact('userData', 'posts'));
    }

    public function fetchPosts__(Request $request)
    {
        $request->validate([
            'limit' => 'integer|min:1|max:50',
            'last_update' => 'nullable|integer',
        ]);

        $limit = $request->input('limit', 10);
        $lastUpdate = $request->input('last_update');

        $query = Post::with([
            'user:id,full_name,role_name,avatar',
            'media',
            'poll.options',
        ])
            ->where('status', 'published');

        if ($lastUpdate) {
            $query->where('updated_at', '>', date('Y-m-d H:i:s', $lastUpdate / 1000));
        }

        $posts = $query->latest()->take($limit)->get()->map(function ($post) {
            return [
                'id' => $post->id,
                'content' => $post->content,
                'type' => $post->type,
                'status' => $post->status,
                'likes_count' => $post->likes_count,
                'comments_count' => $post->comments_count,
                'shares_count' => $post->shares_count,
                'created_at' => $post->created_at,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->full_name,
                    'role' => $post->user->role_name,
                    'avatar' => $post->user->avatar ?? '/default-avatar.png',
                ],
                'media' => $post->media->map(function ($media) {
                    return [
                        'type' => $this->getMediaType($media->path),
                        'path' => $media->path,
                    ];
                }),
                'poll' => $post->poll ? [
                    'id' => $post->poll->id,
                    'question' => $post->poll->question,
                    'options' => $post->poll->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option' => $option->option,
                            'votes' => $option->votes,
                        ];
                    }),
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'posts' => $posts,
            'current_user' => [
                'id' => Auth::id(),
                'role' => Auth::user()->role_name,
            ],
        ]);
    }

    public function deletePost_(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($request->post_id);

        // Vérifier les permissions
        if (Auth::user()->role_name !== 'admin' && $post->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post supprimé avec succès',
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
            $montant >= 100000 => 'diamond',
            $montant >= 20000 => 'gold',
            $montant >= 10000 => 'silver',
            $montant >= 5000 => 'bronze',
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

    public function fetchPosts(Request $request)
    {
        try {
            $request->validate(['limit' => 'integer|min:1|max:50']);
            $limit = $request->input('limit', 10);

            // Cache par utilisateur (5 secondes)
            $cacheKey = 'posts_user_'.Auth::id();

            $data = Cache::remember($cacheKey, 5, function () use ($limit) {
                return $this->loadPosts($limit);
            });

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function loadPosts($limit)
    {
        $posts = Post::with([
            'user:id,full_name,role_name,avatar',
            'media:id,post_id,path',
            'poll.options:id,poll_id,option,votes',
        ])
            ->where('status', 'published')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'content' => $post->content,
                    'type' => $post->type,
                    'likes_count' => $post->likes_count ?? 0,
                    'comments_count' => $post->comments_count ?? 0,
                    'shares_count' => $post->shares_count ?? 0,
                    'created_at' => $post->created_at->toISOString(),
                    'user' => [
                        'id' => $post->user->id,
                        'name' => $post->user->full_name ?? 'Utilisateur',
                        'role' => $post->user->role_name ?? 'user',
                        'avatar' => $post->user->avatar,
                    ],
                    'plaque' => $this->getUserPlaque($post->user_id),
                    'montant' => $this->getUserMontant($post->user_id),
                    'media' => $post->media->map(fn ($m) => [
                        'type' => str_ends_with(strtolower($m->path), '.mp4') ? 'video' : 'image',
                        'path' => $m->path,
                    ]),
                    'poll' => $post->poll ? [
                        'id' => $post->poll->id,
                        'question' => $post->poll->question,
                        'options' => $post->poll->options->map(fn ($o) => [
                            'id' => $o->id,
                            'option' => $o->option,
                            'votes' => $o->votes ?? 0,
                        ]),
                    ] : null,
                ];
            });

        return [
            'success' => true,
            'posts' => $posts->values(),
            'current_user' => [
                'id' => Auth::id(),
                'role' => Auth::user()->role_name ?? 'user',
            ],
        ];
    }

    private function getUserMontant($userId)
    {
        // Cache montant utilisateur (60 secondes)
        return Cache::remember("user_montant_{$userId}", 60, function () use ($userId) {
            return (float) DB::table('trophes')
                ->where('user_id', $userId)
                ->where('status', 'validated')
                ->sum('montant_généré') ?? 0;
        });
    }

    private function getUserPlaque($userId)
    {
        // Cache plaque utilisateur (60 secondes)
        return Cache::remember("user_plaque_{$userId}", 60, function () use ($userId) {
            $montant = $this->getUserMontant($userId);

            return match (true) {
                $montant >= 100000 => 'diamond',
                $montant >= 20000 => 'gold',
                $montant >= 10000 => 'silver',
                $montant >= 5000 => 'bronze',
                default => 'none'
            };
        });
    }

    public function vote(Request $request)
    {
        try {
            $request->validate([
                'poll_id' => 'required|exists:polls,id',
                'option_id' => 'required|exists:poll_options,id',
            ]);

            $option = PollOption::where('id', $request->option_id)
                ->where('poll_id', $request->poll_id)
                ->firstOrFail();

            $option->increment('votes');

            // Invalider cache posts
            $this->clearPostsCache();

            return response()->json(['success' => true, 'votes' => $option->votes]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function share(Request $request)
    {
        try {
            $request->validate(['post_id' => 'required|exists:posts,id']);

            Post::where('id', $request->post_id)->increment('shares_count');

            // Invalider cache posts
            $this->clearPostsCache();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function deletePost(Request $request)
    {
        try {
            $request->validate(['post_id' => 'required|exists:posts,id']);

            $post = Post::findOrFail($request->post_id);

            if (Auth::user()->role_name !== 'admin' && $post->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'error' => 'Non autorisé'], 403);
            }

            $post->delete();

            // Invalider cache posts
            $this->clearPostsCache();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function clearPostsCache()
    {
        // Invalider le cache de tous les utilisateurs
        Cache::flush(); // Simple mais efficace

        // OU plus ciblé si vous avez beaucoup d'utilisateurs :
        // Cache::forget("posts_user_" . Auth::id());
    }

    public function records()
    {
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

        return view('vip.lives.records', compact('userData'));
    }

    public function liveclass()
    {
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
        $instructors = User::whereHas('role', function ($query) {
            $query->where('name', 'teacher');
        })
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();

        return view('vip.lives.live-class', compact('userData', 'user', 'instructors'));
    }
}
