<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Affiliate;
use App\Models\AffiliateCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AffiliateController extends Controller
{
    public function affiliates(Request $request)
    {
        $this->authorize("panel_marketing_affiliates");


        $user = auth()->user();

        $affiliateCode = $user->affiliateCode;

        if (empty($affiliateCode)) {
            $affiliateCode = $this->makeUserAffiliateCode($user);
        }

        $referredUsersCount = Affiliate::where('affiliate_user_id', $user->id)->count();

        $registrationBonus = Accounting::where('is_affiliate_amount', true)
            ->where('system', false)
            ->where('user_id', $user->id)
            ->sum('amount');

        $affiliateBonus = Accounting::where('is_affiliate_commission', true)
            ->where('system', false)
            ->where('user_id', $user->id)
            ->sum('amount');

        $query = Affiliate::where('affiliate_user_id', $user->id)
            ->with(['referredUser'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->has('searchFullName') && !empty($request->searchFullName)) {
            $query->whereHas('referredUser', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->searchFullName . '%');
            });
        }

        if ($request->has('searchEmail') && !empty($request->searchEmail)) {
            $query->whereHas('referredUser', function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->searchEmail . '%');
            });
        }

        if ($request->has('searchStartDate') && !empty($request->searchStartDate) && $request->has('searchEndDate') && !empty($request->searchEndDate)) {
            $query->whereHas('referredUser', function ($query) use ($request) {
                $startDate = strtotime($request->searchStartDate . ' 00:00:00');
                $endDate = strtotime($request->searchEndDate . ' 23:59:59');
                $query->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
            });
        }

        if ($request->has('searchPurchaseStatus')) {
            $purchaseStatus = $request->searchPurchaseStatus;
            $query->whereHas('referredUser', function ($user_query) use ($purchaseStatus) {
                if ($purchaseStatus == 'made') {
                    // Users who have made a purchase with amount greater than 0
                    $user_query->whereHas('buyer_sales', function ($sales_query) {
                        $sales_query->where('amount', '>', 0);
                    });
                } elseif ($purchaseStatus == 'not_made') {
                    // Users who do not have any purchase or have only purchases with amount = 0
                    $user_query->whereDoesntHave('buyer_sales') // No sales records
                          ->orWhere(function ($sales_query) {
                              $sales_query->whereHas('buyer_sales', function ($nested_query) {
                                  $nested_query->where('amount', '=', 0);
                              })->whereDoesntHave('buyer_sales', function ($nested_query) {
                                  $nested_query->where('amount', '>', 0);
                              });
                          });
                }
            });
        }

        $affiliates = $query->paginate(10);

        $data = [
            'pageTitle' => trans('panel.affiliates_page'),
            'affiliateCode' => $affiliateCode,
            'registrationBonus' => $registrationBonus,
            'affiliateBonus' => $affiliateBonus,
            'referredUsersCount' => $referredUsersCount,
            'affiliates' => $affiliates,
        ];

        return view('web.default.panel.marketing.affiliates', $data);
    }

    private function makeUserAffiliateCode($user)
    {
        $code = mt_rand(100000, 999999);

        $check = AffiliateCode::where('code', $code)->first();

        if (!empty($check)) {
            return $this->makeUserAffiliateCode($user);
        }

        $affiliateCode = AffiliateCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'created_at' => time()
        ]);

        return $affiliateCode;
    }
}
