<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;

class RecordedController extends Controller
{
    public function index()
    {
        $data = [
            'pageTitle' => trans('panel.recorded_classes'),
        ];

        return view(getTemplate() . '.panel.webinar.recorded', $data);
    }
}
