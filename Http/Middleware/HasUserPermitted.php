<?php

namespace Modules\Permission\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

//@TODO: is? or has?
class hasUserPermitted
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
        //access control
        if( !auth()->user()->can( $request->route()->getName() ) ){
            return abort(403);
        }

        return $next($request);
    }
}
