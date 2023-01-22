<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddFilters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->route()->parameters());
        $all = $request->all();
        $filters = [];

        foreach ($all as $key => $value) {
            if (in_array($key, [
                'page', 'access_token',
                '_token', 'limit', 'offset',
                'sort', 'include', 'filter'
            ])) {
                continue;
            }
            $filters["filter[{$key}]"] = $value;
        }
        $request->merge($filters);

        return $next($request);
    }
}
