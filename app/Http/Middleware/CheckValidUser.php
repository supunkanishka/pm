<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckValidUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user() === null){
            return redirect()->route('home')->with('success', 'Not Allowed !');
        }

        
        
        if($request->user()->email == 'supunr@insharptechnologies.com' 
            || $request->user()->email == 'hr@insharptechnologies.com'
            || $request->user()->email == 'hr-mgr@insharptechnologies.com'
            || $request->user()->email == 'sithu.sithumi@gmail.com'
        ){
            return $next($request);
        }

        return redirect()->route('home')->with('success', 'Not Allowed !');

        

        
    }
}
