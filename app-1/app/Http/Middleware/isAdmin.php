<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $userRole = auth()->user()->role_id;

        
        if($userRole !== 1){
            return response()->json([
                'message'=>'You are not authorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
