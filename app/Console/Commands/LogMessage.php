<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogMessage extends Command
{
    // The name and signature of the console command.
    protected $signature = 'log:message';

    // The console command description.
    protected $description = 'Log a message "Hi"';

    // Execute the console command.
    public function handle()
    {
        // Logging "Hi"
        Log::info('Hi');
        
        $this->info('Message logged: Hi');
    }
}
