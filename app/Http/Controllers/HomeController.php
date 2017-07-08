<?php

namespace App\Http\Controllers;

use App\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authenticated_user = Auth::user();
        $repositories = Repository::where('user_id', '=', $authenticated_user->id)->get();
        return view('home',
            [
                'repositories' => $repositories
            ]
        );
    }
}
