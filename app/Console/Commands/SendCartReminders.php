<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckCartReminders;

class SendCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les rappels pour les paniers abandonnés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Envoi des rappels de panier...');
        
        // Exécuter la tâche
        CheckCartReminders::dispatch();
        
        $this->info('Rappels envoyés avec succès !');
        
        return Command::SUCCESS;
    }
}
