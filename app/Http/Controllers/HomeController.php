<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index() : View
    {
        return View('home');
    }
}
