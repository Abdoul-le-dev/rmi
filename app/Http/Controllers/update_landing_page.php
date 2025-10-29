<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class update_landing_page extends Controller
{
    public function index(Request $request)
    {
        return view("update.index");
    }

    public function instructeurs(Request $request)
    {
        return view("update.instructeurs");
    }

    public function faq_dev(Request $request)
    {
        return view("update.faq_dev");
    }
     public function communaute_access(Request $request)
    {
        return view("update.communaute-access");
    }

     public function communaute(Request $request)
    {
        return view("update.communaute");
    }

    public function a_propos(Request $request)
    {
        return view("update.a_propos");
    }

    
}
