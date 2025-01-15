<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveOrder'; // Define the correct command signature

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log the removal of orders every minute';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('RemoveOrders command executed.');
    }
}


