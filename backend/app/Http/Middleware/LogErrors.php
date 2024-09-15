<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

class LogErrors
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
        try {
            return $next($request);
        } catch (\Exception $e) {
            $this->logError($request, $e);
            throw $e;
        }
    }

    /**
     * Log the error details.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     */
    protected function logError(Request $request, \Exception $e)
    {
        $status = $e instanceof HttpException ? $e->getStatusCode() : 500;
        $message = $e->getMessage();
        $url = $request->fullUrl();
        $method = $request->method();
        $input = $request->all();

        Log::error("Error occurred: [{$status}] {$message}", [
            'url' => $url,
            'method' => $method,
            'input' => $input,
            'exception' => $e
        ]);

        if ($status === 404) {
            return response()->view('errors.404', [], 404);
        } elseif ($status === 500) {
            return response()->view('errors.500', [], 500);
        }
    }
}
