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

class MyRepository
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
        $repository_id = $request->route('repository_id');
        $repository = Repository::find($repository_id);
        if($repository == null){
            throw new \Exception('The project with id ' . $repository_id . ' does not exists');
        }
        if($repository->user_id != $user->id){
            throw new \Exception('You have not permissions for this operation');
        }
        return $next($request);
    }

}