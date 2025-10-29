<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseModule;
use App\Models\Subscribe;
use App\Models\Translation\SubscribeTranslation;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PurchaseModuleController extends Controller
{
    public function index()
    {
        $purchasemodel = PurchaseModule::with('purchase_sales')->withCount('purchase_sales')->paginate(10);
        Log::info('purchasemodel', ['data' => $purchasemodel]);

        $data = [
            'pageTitle' => trans('admin/pages/financial.purchase'),
            'purchasemodel' => $purchasemodel
        ];

        return view('admin.financial.purchase.lists', $data);
    }

    public function create()
    {

        $subscribes = Subscribe::all();
        Log::info('subscriptions', ['data' => $subscribes]);

        $data = [
            'pageTitle' => trans('admin/pages/financial.new_purchase'),
            'subscribes' => $subscribes
        ];

        return view('admin.financial.purchase.new', $data);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|string',
                'price' => 'required|numeric',
                'actual_price' => 'required|numeric',
                'status' => 'required',
                'subscription' => 'required',
                'is_popular' => 'sometimes|boolean',
            ]);

            $data = $request->all();

            PurchaseModule::create([
                'title' => $data['title'],
                'price' => $data['price'],
                'actual_price' => $data['actual_price'],
                'status' => $data['status'],
                'is_popular' => isset($data['is_popular']) ? (bool)$data['is_popular'] : false,
                'subscribe_id' => $data['subscription'],
                'created_at' => time(),
            ]);

            return redirect(getAdminPanelUrl() . '/financial/purchase-model');
            // return back();
        } catch (\Exception $e) {
            Log::info('error', ['data' => $e->getMessage()]);
            return back();
        }

    }

    public function edit(Request $request, $id)
    {
        $purchasemodel = PurchaseModule::findOrFail($id);
        $subscribes = Subscribe::all();
        // Log::info('subscriptions', ['data'=>$subscribes]);

        $data = [
            'pageTitle' => trans('admin/pages/financial.new_purchase'),
            'purchasemodel' => $purchasemodel,
            'subscribes' => $subscribes
        ];

        return view('admin.financial.purchase.new', $data);
    }

    public function update(Request $request, $id)
    {

        try {
            $this->validate($request, [
                'title' => 'required|string',
                'price' => 'required|numeric',
                'actual_price' => 'required|numeric',
                'status' => 'required',
                'subscription' => 'required',
                'is_popular' => 'sometimes|boolean',
            ]);

            $data = $request->all();
            $purchasemodel = PurchaseModule::findOrFail($id);

            $purchasemodel->update([
                'title' => $data['title'],
                'price' => $data['price'],
                'actual_price' => $data['actual_price'],
                'status' => $data['status'],
                'is_popular' => isset($data['is_popular']) ? (bool)$data['is_popular'] : false,
                'subscribe_id' => $data['subscription'],
                'created_at' => time(),
            ]);

            return redirect(getAdminPanelUrl() . '/financial/purchase-model');
        } catch (\Exception $e) {
            Log::info('error', ['data' => $e->getMessage()]);
        }

    }

    public function delete($id)
    {

        $purchasemodel = PurchaseModule::findOrFail($id);

        $purchasemodel->delete();

        return redirect(getAdminPanelUrl() . '/financial/purchase-model');
    }
}
