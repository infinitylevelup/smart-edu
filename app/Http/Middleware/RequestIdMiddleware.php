<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RequestIdMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->headers->get('X-Request-Id') ?: (string) Str::uuid();

        // در درخواست ذخیره کن تا از Route هم قابل خواندن باشد
        $request->headers->set('X-Request-Id', $requestId);

        // برای لاگ‌ها
        logger()->withContext([
            'request_id' => $requestId,
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        // برای Sentry
        if (app()->bound('sentry')) {
            \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($requestId) {
                $scope->setTag('request_id', $requestId);
            });
        }

        $response = $next($request);

        // در هدر پاسخ هم اضافه کن
        $response->headers->set('X-Request-Id', $requestId);

        return $response;
    }
}
