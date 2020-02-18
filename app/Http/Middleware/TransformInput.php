<?php

namespace App\Http\Middleware;

use Closure;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $transformer
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput = [];
        foreach ($request->request->all() as $input => $value){
            $transformedInput[$transformer::originalAttributes($input)] = $value;
        }
        $request->replace($transformedInput);
        return $next($request);

    }
}
