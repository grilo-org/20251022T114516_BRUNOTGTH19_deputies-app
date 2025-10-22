<?php

namespace App\Jobs;

use App\Models\Deputy;
use App\Services\CamaraService;
use App\Repositories\DeputyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncDeputyExpensesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Deputy $deputy
    ) {}

    public function handle(
        CamaraService $camaraService,
        DeputyRepository $repository
    ): void {
        try {
            $expenses = $camaraService->getDeputyExpenses($this->deputy->external_id);
            
            if (!empty($expenses)) {
                $repository->syncExpenses($this->deputy, $expenses);
                Log::info("Synced expenses for deputy: {$this->deputy->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to sync expenses for deputy {$this->deputy->id}: " . $e->getMessage());
            $this->fail($e);
        }
    }
    
    public function failed(\Throwable $exception): void
    {
        Log::critical("Job failed for deputy {$this->deputy->id}: " . $exception->getMessage());
    }
}