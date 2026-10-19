<?php
namespace App\Http\Middleware;

use Closure;

class LoginMiddleware
{
    public function handle($request, Closure $next)
    {
        if(!($request->input('username') == 'tedc' && $request->input('password') == 'rahasia'
        )) {
            return  "Anda tidak diijikan untuk mengakses data nilai, karena username dan password Anda salah!";
        }
        return $next($request);
    }
}

?>