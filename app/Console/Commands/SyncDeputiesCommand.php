<?php

namespace App\Console\Commands;

use App\Models\Deputy;
use App\Jobs\SyncDeputyExpensesJob;
use App\Services\CamaraService;
use Illuminate\Console\Command;

class SyncDeputiesCommand extends Command
{
    protected $signature = 'deputies:sync {--per-page=100 : Number of deputies per page}';
    protected $description = 'Synchronize deputies and expenses data from Camara API';

    public function handle(CamaraService $camaraService): int
    {
        $perPage = (int) $this->option('per-page');
        
        $this->info("Starting deputies synchronization...");
        $deputies = $camaraService->getDeputies($perPage);

        if (empty($deputies)) {
            $this->error('No deputies found or API error occurred');
            return 1;
        }

        $this->info("Found " . count($deputies) . " deputies. Starting jobs dispatch...");
        
        $bar = $this->output->createProgressBar(count($deputies));
        $bar->start();

        foreach ($deputies as $deputyData) {
            try {
                $deputy = Deputy::updateOrCreate(
                    ['external_id' => $deputyData->external_id],
                    [
                        'name' => $deputyData->name,
                        'party' => $deputyData->party,
                        'state' => $deputyData->state,
                        'photo_url' => $deputyData->photo_url,
                        'email' => $deputyData->email,
                        'legislature_id' => $deputyData->legislature_id
                    ]
                );
                
                SyncDeputyExpensesJob::dispatch($deputy)->onQueue('expenses');
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("Error for deputy {$deputyData->external_id}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Dispatched " . count($deputies) . " jobs for expenses synchronization");
        return 0;
    }
}