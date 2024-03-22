<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;

class RoleMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        $user = $request->user();

        if (!$user) {
            // ? If the user does not have access to the module abort the request
            return abort(403, "You don't have access to this module");
        }

        // ? Get all modules from the user
        $access_modules = $user->roles()->pluck('module');

        // ? loop all and turn the value into an array
        $access_modules = $access_modules->map(function ($module) {
            return explode(',', $module);
        })->flatten();

        // ? Check if the user has access to the module
        if ($access_modules->contains($module)) {
            return $next($request);
        }

        // ? If the user does not have access to the module [403]
        return abort(403, "You don't have access to this module");
    }
}
