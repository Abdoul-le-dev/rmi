<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::paginate(10);
        return view('admin.financial.promo.lists', [
            'pageTitle' => trans('admin/pages/financial.promo'),
            'promos' => $promos,
        ]);
    }

    public function create()
    {
        // $types = ['Subscription', 'Purchase Module'];
        $types = ['Purchase Module'];
        return view('admin.financial.promo.new', [
            'pageTitle' => trans('admin/pages/financial.new_promo'),
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'type' => 'required|string',
        ]);

        Promo::create([
            'title' => $request->title,
            'description' => $request->description,
            'percentage' => $request->percentage,
            'start_date' => Carbon::parse($request->start_date)->startOfDay()->addMinute(), // Set start date to 00:01:00
            'end_date' => Carbon::parse($request->end_date)->endOfDay(), // Set end date to 23:59:59
            'status' => $request->status,
            'type' => $request->type,
            'created_at' => now(),
        ]);

        return redirect(getAdminPanelUrl() . '/financial/promo');
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        $types = ['Purchase Module'];
        // $types = ['Subscription', 'Purchase Module'];
        return view('admin.financial.promo.new', [
            'pageTitle' => trans('admin/pages/financial.edit_promo'),
            'promo' => $promo,
            'types' => $types,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'type' => 'required|string',
        ]);

        $promo = Promo::findOrFail($id);
        $promo->update($request->all());

        return redirect(getAdminPanelUrl() . '/financial/promo');
    }

    public function delete($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return redirect(getAdminPanelUrl() . '/financial/promo');
    }
}
