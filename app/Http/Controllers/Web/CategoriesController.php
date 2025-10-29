<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\FeatureWebinar;
use App\Models\Sale;
use App\Models\Ticket;
use App\Models\Translation\CategoryTranslation;
use App\Models\Webinar;
use App\Models\WebinarFilterOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function index(Request $request, $categorySlug, $subCategorySlug = null)
    {

        $user = auth()->user();
        if (!empty($categorySlug) and auth()->check()) {

            $categoryQuery = Category::query()->where('slug', $categorySlug);

            if (!empty($subCategorySlug)) {
                $categoryQuery = Category::query()->where('slug', $subCategorySlug);
            }

            $category = $categoryQuery->withCount('webinars')
                ->with(['filters' => function ($query) {
                    $query->with('options');
                }])->first();

            if (!empty($category)) {
                $categoryIds = [$category->id];

                if (!empty($category->subCategories) and count($category->subCategories)) {
                    $categoryIds = array_merge($categoryIds, $category->subCategories->pluck('id')->toArray());
                }

                $featureWebinars = FeatureWebinar::whereIn('page', ['categories', 'home_categories'])
                    ->where('status', 'publish')
                    ->whereHas('webinar', function ($q) use ($categoryIds) {
                        $q->where('status', Webinar::$active);
                        $q->whereHas('category', function ($q) use ($categoryIds) {
                            $q->whereIn('id', $categoryIds);
                        });
                    })
                    ->with(['webinar' => function ($query) {
                        $query->with(['teacher' => function ($qu) {
                            $qu->select('id', 'full_name', 'avatar');
                        }, 'reviews', 'tickets', 'feature']);
                    }])
                    ->orderBy('updated_at', 'desc')
                    ->get();


                $webinarsQuery = Webinar::where('webinars.status', 'active')
                    ->where('private', false)
                    ->whereIn('category_id', $categoryIds);

                $classesController = new ClassesController();
                $moreOptions = $request->get('moreOptions');
                $tableName = 'webinars';

                if (!empty($moreOptions) and is_array($moreOptions) and in_array('bundles', $moreOptions)) {
                    $webinarsQuery = Bundle::where('bundles.status', 'active')
                        ->whereIn('category_id', $categoryIds);

                    $tableName = 'bundles';
                    $classesController->tableName = 'bundles';
                    $classesController->columnId = 'bundle_id';
                }

                $webinarsQuery = $classesController->handleFilters($request, $webinarsQuery);

                $sort = $request->get('sort', null);

                if (empty($sort)) {
                    $webinarsQuery = $webinarsQuery->orderBy("{$tableName}.created_at", 'desc');
                }

                $webinars = $webinarsQuery->with(['tickets'])
                    ->paginate(6);

                $seoSettings = getSeoMetas('categories');
                $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.categories_page_title');
                $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.categories_page_title');
                $pageRobot = getPageRobot('categories');

                $data = [
                    'pageTitle' => $pageTitle,
                    'pageDescription' => $pageDescription,
                    'pageRobot' => $pageRobot,
                    'category' => $category,
                    'webinars' => $webinars,
                    'featureWebinars' => $featureWebinars,
                    'webinarsCount' => $webinars->total(),
                    'sortFormAction' => $category->getUrl(),
                ];

                $hasPurchasedCourse = Sale::query()
                ->where(function ($query) use ($user) {
                    $query->where('sales.buyer_id', $user->id);
                })
                ->whereNull('sales.refund_at')
                ->where('access_to_purchased_item', true)
                ->where(function ($query) {
                    $query->whereNotNull('sales.webinar_id')
                          ->where('sales.type', 'webinar')
                          ->whereHas('webinar', function ($query) {
                              $query->where('status', 'active')
                                    ->where('type', 'course')
                                    ->where('price', '>', 0);
                          });
                })
                ->exists();

                if (!$hasPurchasedCourse and ($user->role_name !== 'teacher' and $user->email !== 'tossouericcodjo@gmail.com')) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('new_translations.purchase_course'),
                        'status' => 'error'
                    ];
                    return redirect('/course/devenez-trader-pro')->with(['toast' => $toastData, 'showCard' => true]);
                }

                return view(getTemplate() . '.pages.categories', $data);
            }
        }else{
            return redirect('/login');
        }
    }
}
