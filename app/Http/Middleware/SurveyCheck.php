<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SurveyCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$statuses): Response
    {
        $siswa=auth('web')->user()?->siswa;
        $status=$statuses[0];

        

        if($siswa==null) return back();
        if($siswa->need_survey!=$status)
        {
            if($siswa->need_survey===0) return redirect()->route('siswa.dashboard');
            return redirect()->route('dass21.form');
        }

        return $next($request);
    
    }
}
