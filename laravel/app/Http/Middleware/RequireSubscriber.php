<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireSubscriber
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()?->hasActiveSubscription()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'subscription_required',
                    'message' => 'Esta funcionalidad requiere el Plan Pro.',
                    'upgrade_url' => route('subscription.plans'),
                ], 403);
            }

            return redirect()->route('subscription.plans')
                ->with('info', 'El análisis de viabilidad y la extracción de pólizas están incluidos en el Plan Pro (29,99 €/mes).');
        }

        return $next($request);
    }
}
