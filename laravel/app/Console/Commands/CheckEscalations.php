<?php

namespace App\Console\Commands;

use App\Jobs\EscalateClaimJob;
use App\Models\Claim;
use Illuminate\Console\Command;

class CheckEscalations extends Command
{
    protected $signature = 'reclamaia:check-escalations {--dry-run : Show what would be escalated without dispatching jobs}';
    protected $description = 'Check completed claims that have not received a response in 30 days and dispatch escalation letters';

    public function handle(): void
    {
        $thirtyDaysAgo = now()->subDays(30);

        // Claims that were marked as sent to insurer 30+ days ago with no escalation
        $claimsToEscalate = Claim::where('status', Claim::STATUS_COMPLETED)
            ->where('escalation_status', 'none')
            ->where('sent_to_insurer_at', '<=', $thirtyDaysAgo)
            ->whereNotNull('sent_to_insurer_at')
            ->get();

        if ($claimsToEscalate->isEmpty()) {
            $this->info('No hay reclamaciones para escalar.');
            return;
        }

        $this->info("Encontradas {$claimsToEscalate->count()} reclamaciones para escalar:");

        foreach ($claimsToEscalate as $claim) {
            $days = (int) $claim->sent_to_insurer_at->diffInDays(now());
            $this->line("  → Claim #{$claim->id} | {$claim->insurer_name} | {$days} días sin respuesta");

            if (!$this->option('dry-run')) {
                EscalateClaimJob::dispatch($claim->id);
            }
        }

        if ($this->option('dry-run')) {
            $this->warn('Modo dry-run: no se han despachado trabajos.');
        } else {
            $this->info('Jobs despachados. Los documentos de escalada se generarán en segundos.');
        }
    }
}
