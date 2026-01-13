<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TenantService;

class AuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Audit only write operations or specific sensitive reads if needed
        // For now, let's audit non-GET methods for SuperAdmins or important actions
        if (auth()->check() && !in_array($request->method(), ['GET', 'HEAD'])) {
            $service = app(TenantService::class);
            
            // Simple logging of the request path and method
            // In a real app, we might capture more details or hook into Eloquent events
            $service->logAction(
                $request->method(), 
                null, 
                null, 
                ['path' => $request->path(), 'input' => $request->except(['password', 'password_confirmation'])]
            );
        }

        return $response;
    }
}
