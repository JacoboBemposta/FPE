<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\StripeSyncController;

class SyncStripeSubscriptions extends Command
{
    protected $signature = 'stripe:sync';
    protected $description = 'Sincronizar suscripciones desde Stripe';

    public function handle()
    {
        $controller = new StripeSyncController();
        $result = $controller->syncAllSubscriptions();
        
        if ($result->original['success']) {
            $this->info($result->original['message']);
        } else {
            $this->error($result->original['message']);
        }
    }
}