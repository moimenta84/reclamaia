<?php

namespace App\Http\Middleware;

use App\Jobs\GenerateDocumentJob;
use App\Models\Claim;
use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->hasActiveSubscription()) {
            $claimId = $request->route('claim')?->id ?? $request->route('claim');
            if ($claimId) {
                $claim = Claim::find($claimId);
                if ($claim && $claim->status === Claim::STATUS_PENDING) {
                    $claim->update(['status' => Claim::STATUS_PROCESSING]);
                    GenerateDocumentJob::dispatch($claim->id);
                    return redirect()->route('claim.download', $claim)
                        ->with('info', 'Generando tu reclamación (plan Pro activo)...');
                }
            }
        }

        return $next($request);
    }
}
