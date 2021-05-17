<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminCheck
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
        if ($request->path()=='app/admin_login') {
            return $next($request);
        }
        if (!Auth::check()) {
            
              return response()->json([
                 'msg' => 'You have no accsess to this route ..BE GONE!',
               ],402);
              }
        $user = Auth::user();
        if($user->usertype=="User"){
            return response()->json([
                'msg' => 'You have no accsess to this route ..BE GONE!',
             ],402);
        }
        if($user->role->isAdmin==0){
            return response()->json([
                'msg' => 'You have no accsess to this route ..BE GONE!',
             ],402);
        }
        
           
        return $next($request);
    }
    
}



