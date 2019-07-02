<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Factory as Auth;

class UserNameMiddleware
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
         $c = explode('/', $request->path());
         $result = end($c);
         $username = DB::table('users')
                        ->where('username', $result)
                        ->exists();
         if (!$username) {
              return response()->json('User name does not exist');
         }
          return $next($request);
      }

}
