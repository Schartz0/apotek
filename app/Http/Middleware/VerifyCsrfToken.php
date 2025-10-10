<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Log the error for debugging
            Log::error('CSRF Token Mismatch', [
                'url' => $request->url(),
                'method' => $request->method(),
                'token_from_request' => $request->input('_token'),
                'session_token' => $request->session()->token(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Redirect back with error message
            return redirect()->back()
                ->withInput($request->except('_token', 'password'))
                ->with('error', 'Halaman sudah expired. Silakan coba lagi.');
        }
    }
}
