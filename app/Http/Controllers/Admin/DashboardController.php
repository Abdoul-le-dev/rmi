<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\traits\DashboardTrait;
use App\Http\Controllers\Controller;
use App\Models\FeatureWebinar;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Ticket;
use App\Models\Webinar;
use App\Models\GroupUser;
use App\Models\Meeting;
use App\Models\ReserveMeeting;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Exports\ActiveSalesExport;
use App\Exports\ExpiredSubscriptionsExport;
use App\Exports\UsersWithoutSalesExport;
use App\Exports\UsersWithWebinarOnlyExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    use DashboardTrait;

    protected function cacheIfAllowed(string $ability, string $key, int $ttlSeconds, callable $resolver, $default = null)
{
    if (!Gate::allows($ability)) {
        return $default;
    }

    $user = auth()->user();
    $locale = app()->getLocale();
    $tz = config('app.timezone'); // ou $user->timezone si tu l’as

    // Clé de cache "namespacée" par user/locale/tz pour éviter toute fuite
    $scopedKey = implode(':', [
        'dash', $key,
        'u'.$user->id,
        'loc'.$locale,
        'tz'.$tz,
    ]);

    // Tags pour purge ciblée via le bouton "clear cache"
    return Cache::tags(['dashboard', 'user:'.$user->id])
        ->remember($scopedKey, $ttlSeconds, function () use ($resolver, $key) {
            $t0 = microtime(true);
            $result = $resolver();
            \Log::info("[DASH] computed '{$key}' in ".round((microtime(true)-$t0)*1000,2).'ms');
            return $result;
        });
}

    public function index()
    {   
       
        $this->authorize('admin_general_dashboard_show');

        if (Gate::allows('admin_general_dashboard_daily_sales_statistics')) {
            $dailySalesTypeStatistics = $this->dailySalesTypeStatistics();
        }

        if (Gate::allows('admin_general_dashboard_income_statistics')) {
            $getIncomeStatistics = $this->getIncomeStatistics();
        }

        if (Gate::allows('admin_general_dashboard_total_sales_statistics')) {
            $getTotalSalesStatistics = $this->getTotalSalesStatistics();
        }

        if (Gate::allows('admin_general_dashboard_new_sales')) {
            $getNewSalesCount = $this->getNewSalesCount();
        }

        if (Gate::allows('admin_general_dashboard_new_comments')) {
            $getNewCommentsCount = $this->getNewCommentsCount();
        }

        if (Gate::allows('admin_general_dashboard_new_tickets')) {
            $getNewTicketsCount = $this->getNewTicketsCount();
        }

        if (Gate::allows('admin_general_dashboard_new_reviews')) {
            $getPendingReviewCount = $this->getPendingReviewCount();
        }

        if (Gate::allows('admin_general_dashboard_sales_statistics_chart')) {
            $getMonthAndYearSalesChart = $this->getMonthAndYearSalesChart('month_of_year');
            $getMonthAndYearSalesChartStatistics = $this->getMonthAndYearSalesChartStatistics();
        }

        if (Gate::allows('admin_general_dashboard_recent_comments')) {
            $recentComments = $this->getRecentComments();
        }

        if (Gate::allows('admin_general_dashboard_recent_tickets')) {
            $recentTickets = $this->getRecentTickets();
        }

        if (Gate::allows('admin_general_dashboard_recent_webinars')) {
            $recentWebinars = $this->getRecentWebinars();
        }

        if (Gate::allows('admin_general_dashboard_recent_courses')) {
            $recentCourses = $this->getRecentCourses();
        }

        if (Gate::allows('admin_general_dashboard_users_statistics_chart')) {
            $usersStatisticsChart = $this->usersStatisticsChart();
        }

        $data = [
            'pageTitle' => trans('admin/main.general_dashboard_title'),
            'dailySalesTypeStatistics' => $dailySalesTypeStatistics ?? null,
            'getIncomeStatistics' => $getIncomeStatistics ?? null,
            'getTotalSalesStatistics' => $getTotalSalesStatistics ?? null,
            'getNewSalesCount' => $getNewSalesCount ?? 0,
            'getNewCommentsCount' => $getNewCommentsCount ?? 0,
            'getNewTicketsCount' => $getNewTicketsCount ?? 0,
            'getPendingReviewCount' => $getPendingReviewCount ?? 0,
            'getMonthAndYearSalesChart' => $getMonthAndYearSalesChart ?? null,
            'getMonthAndYearSalesChartStatistics' => $getMonthAndYearSalesChartStatistics ?? null,
            'recentComments' => $recentComments ?? null,
            'recentTickets' => $recentTickets ?? null,
            'recentWebinars' => $recentWebinars ?? null,
            'recentCourses' => $recentCourses ?? null,
            'usersStatisticsChart' => $usersStatisticsChart ?? null,
        ];

        

        return view('admin.dashboard', $data);
        
    }

    public function indext()
{
    try {
        $this->authorize('admin_general_dashboard_show');
        \Log::info('--- Dashboard start ---');

        if (Gate::allows('admin_general_dashboard_daily_sales_statistics')) {
            $dailySalesTypeStatistics = $this->dailySalesTypeStatistics();
            \Log::info('✓ dailySalesTypeStatistics OK');
        }

        if (Gate::allows('admin_general_dashboard_income_statistics')) {
            $getIncomeStatistics = $this->getIncomeStatistics();
            \Log::info('✓ getIncomeStatistics OK');
        }

        if (Gate::allows('admin_general_dashboard_total_sales_statistics')) {
            $getTotalSalesStatistics = $this->getTotalSalesStatistics();
            \Log::info('✓ getTotalSalesStatistics OK');
        }

        if (Gate::allows('admin_general_dashboard_new_sales')) {
            $getNewSalesCount = $this->getNewSalesCount();
            \Log::info('✓ getNewSalesCount OK');
        }

        if (Gate::allows('admin_general_dashboard_new_comments')) {
            $getNewCommentsCount = $this->getNewCommentsCount();
            \Log::info('✓ getNewCommentsCount OK');
        }

        if (Gate::allows('admin_general_dashboard_new_tickets')) {
            $getNewTicketsCount = $this->getNewTicketsCount();
            \Log::info('✓ getNewTicketsCount OK');
        }

        if (Gate::allows('admin_general_dashboard_new_reviews')) {
            $getPendingReviewCount = $this->getPendingReviewCount();
            \Log::info('✓ getPendingReviewCount OK');
        }

        if (Gate::allows('admin_general_dashboard_sales_statistics_chart')) {
            $getMonthAndYearSalesChart = $this->getMonthAndYearSalesChart('month_of_year');
            $getMonthAndYearSalesChartStatistics = $this->getMonthAndYearSalesChartStatistics();
            \Log::info('✓ getMonthAndYearSalesChart OK');
        }

        if (Gate::allows('admin_general_dashboard_recent_comments')) {
            $recentComments = $this->getRecentComments();
            \Log::info('✓ getRecentComments OK');
        }

        if (Gate::allows('admin_general_dashboard_recent_tickets')) {
            $recentTickets = $this->getRecentTickets();
            \Log::info('✓ getRecentTickets OK');
        }

        if (Gate::allows('admin_general_dashboard_recent_webinars')) {
            $recentWebinars = $this->getRecentWebinars();
            \Log::info('✓ getRecentWebinars OK');
        }

        if (Gate::allows('admin_general_dashboard_recent_courses')) {
            $recentCourses = $this->getRecentCourses();
            \Log::info('✓ getRecentCourses OK');
        }

        if (Gate::allows('admin_general_dashboard_users_statistics_chart')) {
            $usersStatisticsChart = $this->usersStatisticsChart();
            \Log::info('✓ usersStatisticsChart OK');
        }

        \Log::info('--- Dashboard data prepared ---');

        // ✅ Toutes les variables rassemblées ici
        $data = [
            'pageTitle' => trans('admin/main.general_dashboard_title'),
            'dailySalesTypeStatistics' => $dailySalesTypeStatistics ?? null,
            'getIncomeStatistics' => $getIncomeStatistics ?? null,
            'getTotalSalesStatistics' => $getTotalSalesStatistics ?? null,
            'getNewSalesCount' => $getNewSalesCount ?? 0,
            'getNewCommentsCount' => $getNewCommentsCount ?? 0,
            'getNewTicketsCount' => $getNewTicketsCount ?? 0,
            'getPendingReviewCount' => $getPendingReviewCount ?? 0,
            'getMonthAndYearSalesChart' => $getMonthAndYearSalesChart ?? null,
            'getMonthAndYearSalesChartStatistics' => $getMonthAndYearSalesChartStatistics ?? null,
            'recentComments' => $recentComments ?? null,
            'recentTickets' => $recentTickets ?? null,
            'recentWebinars' => $recentWebinars ?? null,
            'recentCourses' => $recentCourses ?? null,
            'usersStatisticsChart' => $usersStatisticsChart ?? null,
        ];

        \Log::info('--- Dashboard view render ---');

        return view('admin.dashboard', $data);

    } catch (\Throwable $e) {
        // ⚠️ Capture toute erreur (DB, mémoire, timeout Laravel)
        \Log::error('Dashboard error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        // (optionnel) renvoie une vue simple pour éviter un 504 silencieux
        return response()->view('errors.500', ['message' => 'Erreur Dashboard : '.$e->getMessage()], 500);
    }
}
public function index_1()
{
    try {
        $this->authorize('admin_general_dashboard_show');
        
        $startTime = microtime(true);
        \Log::info('--- Dashboard start ---');

        // Durée de cache : 5 minutes (300 secondes)
        $cacheMinutes = 5;
        
        // Récupérer toutes les données avec cache
        $data = [
            'pageTitle' => trans('admin/main.general_dashboard_title'),
            
            'dailySalesTypeStatistics' => Gate::allows('admin_general_dashboard_daily_sales_statistics') 
                ? Cache::remember('dashboard.daily_sales', $cacheMinutes * 60, fn() => $this->dailySalesTypeStatistics())
                : null,
            
            'getIncomeStatistics' => Gate::allows('admin_general_dashboard_income_statistics')
                ? Cache::remember('dashboard.income_stats', $cacheMinutes * 60, fn() => $this->getIncomeStatistics())
                : null,
            
            'getTotalSalesStatistics' => Gate::allows('admin_general_dashboard_total_sales_statistics')
                ? Cache::remember('dashboard.total_sales', $cacheMinutes * 60, fn() => $this->getTotalSalesStatistics())
                : null,
            
            'getNewSalesCount' => Gate::allows('admin_general_dashboard_new_sales')
                ? Cache::remember('dashboard.new_sales_count', $cacheMinutes * 60, fn() => $this->getNewSalesCount())
                : 0,
            
            'getNewCommentsCount' => Gate::allows('admin_general_dashboard_new_comments')
                ? Cache::remember('dashboard.new_comments_count', $cacheMinutes * 60, fn() => $this->getNewCommentsCount())
                : 0,
            
            'getNewTicketsCount' => Gate::allows('admin_general_dashboard_new_tickets')
                ? Cache::remember('dashboard.new_tickets_count', $cacheMinutes * 60, fn() => $this->getNewTicketsCount())
                : 0,
            
            'getPendingReviewCount' => Gate::allows('admin_general_dashboard_new_reviews')
                ? Cache::remember('dashboard.pending_review_count', $cacheMinutes * 60, fn() => $this->getPendingReviewCount())
                : 0,
            
            'getMonthAndYearSalesChart' => Gate::allows('admin_general_dashboard_sales_statistics_chart')
                ? Cache::remember('dashboard.sales_chart', $cacheMinutes * 60, fn() => $this->getMonthAndYearSalesChart('month_of_year'))
                : null,
            
            'getMonthAndYearSalesChartStatistics' => Gate::allows('admin_general_dashboard_sales_statistics_chart')
                ? Cache::remember('dashboard.sales_chart_stats', $cacheMinutes * 60, fn() => $this->getMonthAndYearSalesChartStatistics())
                : null,
            
            'recentComments' => Gate::allows('admin_general_dashboard_recent_comments')
                ? Cache::remember('dashboard.recent_comments', $cacheMinutes * 60, fn() => $this->getRecentComments())
                : null,
            
            'recentTickets' => Gate::allows('admin_general_dashboard_recent_tickets')
                ? Cache::remember('dashboard.recent_tickets', $cacheMinutes * 60, fn() => $this->getRecentTickets())
                : null,
            
            'recentWebinars' => Gate::allows('admin_general_dashboard_recent_webinars')
                ? Cache::remember('dashboard.recent_webinars', $cacheMinutes * 60, fn() => $this->getRecentWebinars())
                : null,
            
            'recentCourses' => Gate::allows('admin_general_dashboard_recent_courses')
                ? Cache::remember('dashboard.recent_courses', $cacheMinutes * 60, fn() => $this->getRecentCourses())
                : null,
            
            'usersStatisticsChart' => Gate::allows('admin_general_dashboard_users_statistics_chart')
                ? Cache::remember('dashboard.users_chart', $cacheMinutes * 60, fn() => $this->usersStatisticsChart())
                : null,
        ];

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        \Log::info("--- Dashboard loaded in {$executionTime}ms ---");

        return view('admin.dashboard', $data);

    } catch (\Throwable $e) {
        \Log::error('Dashboard error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->view('errors.500', [
            'message' => 'Le tableau de bord ne peut pas être chargé. Veuillez réessayer dans quelques instants.'
        ], 500);
    }
}

public function indexs()
{
    try {
        $this->authorize('admin_general_dashboard_show');

        $tStart = microtime(true);
        \Log::info('--- Dashboard start ---');

        // TTL base (secondes)
        $base = 300; // 5 min
        $jitter = random_int(0, 60); // anti-stampede

        // TTL plus longs pour les stats “peu volatiles”
        $ttlFast  = $base + $jitter;        // 5–6 min
        $ttlSlow  = 900 + $jitter;          // 15–16 min (ex: par mois/année)
        $ttlBrief = 120 + $jitter;          // 2–3 min (compteurs “new”)

        $data = [
            'pageTitle' => trans('admin/main.general_dashboard_title'),

            'dailySalesTypeStatistics' => $this->cacheIfAllowed(
                'admin_general_dashboard_daily_sales_statistics',
                'daily_sales', $ttlFast,
                fn() => $this->dailySalesTypeStatistics()
            ),

            'getIncomeStatistics' => $this->cacheIfAllowed(
                'admin_general_dashboard_income_statistics',
                'income_stats', $ttlFast,
                fn() => $this->getIncomeStatistics()
            ),

            'getTotalSalesStatistics' => $this->cacheIfAllowed(
                'admin_general_dashboard_total_sales_statistics',
                'total_sales', $ttlFast,
                fn() => $this->getTotalSalesStatistics()
            ),

            'getNewSalesCount' => $this->cacheIfAllowed(
                'admin_general_dashboard_new_sales',
                'new_sales_count', $ttlBrief,
                fn() => $this->getNewSalesCount(), 0
            ),

            'getNewCommentsCount' => $this->cacheIfAllowed(
                'admin_general_dashboard_new_comments',
                'new_comments_count', $ttlBrief,
                fn() => $this->getNewCommentsCount(), 0
            ),

            'getNewTicketsCount' => $this->cacheIfAllowed(
                'admin_general_dashboard_new_tickets',
                'new_tickets_count', $ttlBrief,
                fn() => $this->getNewTicketsCount(), 0
            ),

            'getPendingReviewCount' => $this->cacheIfAllowed(
                'admin_general_dashboard_new_reviews',
                'pending_review_count', $ttlBrief,
                fn() => $this->getPendingReviewCount(), 0
            ),

            'getMonthAndYearSalesChart' => $this->cacheIfAllowed(
                'admin_general_dashboard_sales_statistics_chart',
                'sales_chart', $ttlSlow,
                fn() => $this->getMonthAndYearSalesChart('month_of_year')
            ),

            'getMonthAndYearSalesChartStatistics' => $this->cacheIfAllowed(
                'admin_general_dashboard_sales_statistics_chart',
                'sales_chart_stats', $ttlSlow,
                fn() => $this->getMonthAndYearSalesChartStatistics()
            ),

            'recentComments' => $this->cacheIfAllowed(
                'admin_general_dashboard_recent_comments',
                'recent_comments', $ttlFast,
                fn() => $this->getRecentComments()
            ),

            'recentTickets' => $this->cacheIfAllowed(
                'admin_general_dashboard_recent_tickets',
                'recent_tickets', $ttlFast,
                fn() => $this->getRecentTickets()
            ),

            'recentWebinars' => $this->cacheIfAllowed(
                'admin_general_dashboard_recent_webinars',
                'recent_webinars', $ttlFast,
                fn() => $this->getRecentWebinars()
            ),

            'recentCourses' => $this->cacheIfAllowed(
                'admin_general_dashboard_recent_courses',
                'recent_courses', $ttlFast,
                fn() => $this->getRecentCourses()
            ),

            'usersStatisticsChart' => $this->cacheIfAllowed(
                'admin_general_dashboard_users_statistics_chart',
                'users_chart', $ttlSlow,
                fn() => $this->usersStatisticsChart()
            ),
        ];

        \Log::info('--- Dashboard sections computed in '.round((microtime(true)-$tStart)*1000,2).'ms ---');

        return view('admin.dashboard', $data);

    } catch (\Throwable $e) {
        \Log::error('Dashboard error: '.$e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->view('errors.500', [
            'message' => 'Le tableau de bord ne peut pas être chargé. Veuillez réessayer dans quelques instants.'
        ], 500);
    }
}


    public function marketing()
    {
        $this->authorize('admin_marketing_dashboard_show');

        $buyerIds = Sale::whereNull('refund_at')
            ->pluck('buyer_id')
            ->toArray();
        $teacherIdsHasClass = Webinar::where('status', Webinar::$active)
            ->pluck('creator_id', 'teacher_id')
            ->toArray();
        $teacherIdsHasClass = array_merge(array_keys($teacherIdsHasClass), $teacherIdsHasClass);


        $usersWithoutPurchases = User::whereNotIn('id', array_unique($buyerIds))->count();
        $teachersWithoutClass = User::where('role_name', Role::$teacher)
            ->whereNotIn('id', array_unique($teacherIdsHasClass))
            ->count();
        $featuredClasses = FeatureWebinar::where('status', 'publish')
            ->count();

        $now = time();
        $activeDiscounts = Ticket::where('start_date', '<', $now)
            ->where('end_date', '>', $now)
            ->count();

        $sales = Sale::where('type', 'subscribe')
            ->where('expires_at', '>', Carbon::now()->timestamp)
            ->whereHas('buyer', function ($query) {
                $query->where('status', 'active'); // Filter users by active status
            })
            ->with('buyer') // Eager load the related User model
            ->paginate(10);

        // Log::info('Sales', $sales->toArray());
        // Log::info('date', ['data' => Carbon::now()->timestamp]);

        $expired_subscriptions = Sale::where('type', 'subscribe')
            ->where('expires_at', '<', Carbon::now()->timestamp)
            ->whereHas('buyer', function ($query) {
                $query->where('status', 'active'); // Filter users by active status
            })
            ->with('buyer') // Eager load the related User model
            ->paginate(10);

        // Log::info('expired_subscriptions', $expired_subscriptions->toArray());
        // Log::info('date', ['data' => Carbon::now()->timestamp]);

        $usersWithoutSales = User::where('role_name', 'user')
            ->doesntHave('buyer_sales')
            ->paginate(10);

        // Log::info('usersWithoutSales', $usersWithoutSales->toArray());

        $usersWithWebinarOnly = User::whereHas('buyer_sales', function ($query) {
            $query->where('type', 'webinar');
        })
            ->whereDoesntHave('buyer_sales', function ($query) {
                $query->where('type', 'subscribe');
            })
            ->paginate(10);

        // Log::info('usersWithWebinarOnly', $usersWithWebinarOnly->toArray());

        $getClassesStatistics = $this->getClassesStatistics();

        $getNetProfitChart = $this->getNetProfitChart();

        $getNetProfitStatistics = $this->getNetProfitStatistics();

        $getTopSellingClasses = $this->getTopSellingClasses();

        $getTopSellingAppointments = $this->getTopSellingAppointments();

        $getTopSellingTeachers = $this->getTopSellingTeachersAndOrganizations('teachers');

        $getTopSellingOrganizations = $this->getTopSellingTeachersAndOrganizations('organizations');

        $getMostActiveStudents = $this->getMostActiveStudents();

        $data = [
            'pageTitle' => trans('admin/main.marketing_dashboard_title'),
            'usersWithoutPurchases' => $usersWithoutPurchases,
            'teachersWithoutClass' => $teachersWithoutClass,
            'featuredClasses' => $featuredClasses,
            'activeDiscounts' => $activeDiscounts,
            'getClassesStatistics' => $getClassesStatistics,
            'getNetProfitChart' => $getNetProfitChart,
            'getNetProfitStatistics' => $getNetProfitStatistics,
            'getTopSellingClasses' => $getTopSellingClasses,
            'getTopSellingAppointments' => $getTopSellingAppointments,
            'getTopSellingTeachers' => $getTopSellingTeachers,
            'getTopSellingOrganizations' => $getTopSellingOrganizations,
            'getMostActiveStudents' => $getMostActiveStudents,
            'sales' => $sales,
            'expired_subscriptions' => $expired_subscriptions,
            'usersWithoutSales' => $usersWithoutSales,
            'usersWithWebinarOnly' => $usersWithWebinarOnly,
        ];

        return view('admin.marketing_dashboard', $data);
    }

    public function getSaleStatisticsData(Request $request)
    {
        $this->authorize('admin_general_dashboard_sales_statistics_chart');

        $type = $request->get('type');

        $chart = $this->getMonthAndYearSalesChart($type);

        return response()->json([
            'code' => 200,
            'chart' => $chart
        ], 200);
    }

    public function getNetProfitChartAjax(Request $request)
    {

        $type = $request->get('type');

        $chart = $this->getNetProfitChart($type);

        return response()->json([
            'code' => 200,
            'chart' => $chart
        ], 200);
    }

    public function cacheClear()
    {
        $this->authorize('admin_clear_cache');

        Artisan::call('clear:all', [
            '--force' => true
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => 'Website cache successfully cleared.',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function exportActiveSubscribedUsers()
    {
        return Excel::download(new ActiveSalesExport, 'active_subscribed_users.xlsx');
    }

    public function exportExpiredSubscriptions()
    {
        return Excel::download(new ExpiredSubscriptionsExport, 'expired_subscriptions.xlsx');
    }

    public function exportUsersWithoutSales()
    {
        return Excel::download(new UsersWithoutSalesExport, 'users_without_sales.xlsx');
    }

    public function exportUsersWithWebinarOnly()
    {
        return Excel::download(new UsersWithWebinarOnlyExport, 'users_with_webinar_only.xlsx');
    }

    public function suscriber_index()
    {
        $data = [
            'pageTitle' => 'suscriber'
        ];
        return view('admin/teams/suscriber', $data);
    }

    public function suscriber_search(Request $request)
    {
        //dd($request);
        $this->authorize('admin_users_list');
        $query = User::where('role_name', Role::$user);
        $query = $this->filters($query, $request);
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $users = $this->addUsersExtraInfo($users);

        

        return response()->json([
            'user'=> $users
        ]);
    }

     private function addUsersExtraInfo($users)
    {
        foreach ($users as $user) {
            $salesQuery = Sale::where('seller_id', $user->id)
                ->whereNull('refund_at');

            $classesSaleQuery = deepClone($salesQuery)->whereNotNull('webinar_id')
                ->whereNull('meeting_id')
                ->whereNull('promotion_id')
                ->whereNull('subscribe_id');

            $user->classesSalesCount = $classesSaleQuery->count();
            $user->classesSalesSum = $classesSaleQuery->sum('total_amount');

            $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
            $reserveMeetingsQuery = ReserveMeeting::whereIn('meeting_id', $meetingIds)
                ->where(function ($query) {
                    $query->whereHas('sale', function ($query) {
                        $query->whereNull('refund_at');
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('status', ['canceled']);
                        $query->whereHas('sale');
                    });
                });

            $user->meetingsSalesCount = deepClone($reserveMeetingsQuery)->count();
            $user->meetingsSalesSum = deepClone($reserveMeetingsQuery)->sum('paid_amount');


            $purchasedQuery = Sale::where('buyer_id', $user->id)
                ->whereNull('refund_at');

            $classesPurchasedQuery = deepClone($purchasedQuery)->whereNotNull('webinar_id')
                ->whereNull('meeting_id')
                ->whereNull('promotion_id')
                ->whereNull('subscribe_id');

            $user->classesPurchasedsCount = $classesPurchasedQuery->count();
            $user->classesPurchasedsSum = $classesPurchasedQuery->sum('total_amount');

            $meetingsPurchasedQuery = deepClone($purchasedQuery)->whereNotNull('meeting_id')
                ->whereNull('webinar_id')
                ->whereNull('promotion_id')
                ->whereNull('subscribe_id');

            $user->meetingsPurchasedsCount = $meetingsPurchasedQuery->count();
            $user->meetingsPurchasedsSum = $meetingsPurchasedQuery->sum('total_amount');
        }

        return $users;
    }

    private function filters($query, $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $full_name = $request->get('full_name');
        $email = $request->get('email');  // Add email filter variable
        $mobile = $request->get('mobile');  // Add mobile filter variable
        $sort = $request->get('sort');
        $group_id = $request->get('group_id');
        $status = $request->get('status');
        $role_id = $request->get('role_id');
        $organization_id = $request->get('organization_id');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($full_name)) {
            $query->where('full_name', 'like', "%$full_name%");
        }

        if (!empty($email)) {
            $query->where('email', 'like', "%$email%");
        }
    
        if (!empty($mobile)) {
            $query->where('mobile', 'like', "%$mobile%");
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'sales_classes_asc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.webinar_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.webinar_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'asc');
                    break;
                case 'sales_classes_desc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.webinar_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.webinar_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'desc');
                    break;
                case 'purchased_classes_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_count', 'asc');
                    break;
                case 'purchased_classes_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->groupBy('sales.buyer_id')
                        ->whereNull('sales.refund_at')
                        ->orderBy('purchased_count', 'desc');
                    break;
                case 'purchased_classes_amount_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->groupBy('sales.buyer_id')
                        ->whereNull('sales.refund_at')
                        ->orderBy('purchased_amount', 'asc');
                    break;
                case 'purchased_classes_amount_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->groupBy('sales.buyer_id')
                        ->whereNull('sales.refund_at')
                        ->orderBy('purchased_amount', 'desc');
                    break;
                case 'sales_appointments_asc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'asc');
                    break;
                case 'sales_appointments_desc':
                    $query->join('sales', 'users.id', '=', 'sales.seller_id')
                        ->select('users.*', 'sales.seller_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.seller_id) as sales_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.seller_id')
                        ->orderBy('sales_count', 'desc');
                    break;
                    
                case 'purchased_appointments_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_count', 'asc');
                    break;
                case 'purchased_appointments_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.meeting_id', 'sales.refund_at', DB::raw('count(sales.buyer_id) as purchased_count'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_count', 'desc');
                    break;
                case 'purchased_appointments_amount_asc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.meeting_id', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_amount', 'asc');
                    break;
                case 'purchased_appointments_amount_desc':
                    $query->join('sales', 'users.id', '=', 'sales.buyer_id')
                        ->select('users.*', 'sales.buyer_id', 'sales.amount', 'sales.meeting_id', 'sales.refund_at', DB::raw('sum(sales.amount) as purchased_amount'))
                        ->whereNotNull('sales.meeting_id')
                        ->whereNull('sales.refund_at')
                        ->groupBy('sales.buyer_id')
                        ->orderBy('purchased_amount', 'desc');
                    break;
                case 'register_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'register_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        if (!empty($group_id)) {
            $userIds = GroupUser::where('group_id', $group_id)->pluck('user_id')->toArray();

            $query->whereIn('id', $userIds);
        }

        if (!empty($status)) {
            switch ($status) {
                case 'active_verified':
                    $query->where('status', 'active')
                        ->where('verified', true);
                    break;
                case 'active_notVerified':
                    $query->where('status', 'active')
                        ->where('verified', false);
                    break;
                case 'inactive':
                    $query->where('status', 'inactive');
                    break;
                case 'ban':
                    $query->where('ban', true)
                        ->whereNotNull('ban_end_at')
                        ->where('ban_end_at', '>', time());
                    break;
            }
        }

        if (!empty($role_id)) {
            $query->where('role_id', $role_id);
        }

        if (!empty($organization_id)) {
            $query->where('organ_id', $organization_id);
        }

        //dd($query->get());
        return $query;
    }
}
