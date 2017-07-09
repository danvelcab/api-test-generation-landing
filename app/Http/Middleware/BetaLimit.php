<?php

namespace App\Http\Middleware;

use App\FormMessage;
use App\Promo;
use App\Providers\CodesServiceProvider;
use App\Providers\PromosServiceProvider;
use App\Repository;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class BetaLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $repositories = Repository::all();
        if(count($repositories) > 0 && !$user->admin){
            return redirect()->back()->with([
                'error' => 'API Test Generator is still in beta. At the moment, you can only create one project per account'
            ]);
        }
        return $next($request);
    }

}