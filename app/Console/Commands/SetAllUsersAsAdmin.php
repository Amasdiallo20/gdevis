<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetAllUsersAsAdmin extends Command
{
    protected $signature = 'user:set-all-admin';
    protected $description = 'Définir tous les utilisateurs existants comme administrateurs';

    public function handle()
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->info("Aucun utilisateur trouvé.");
            return 0;
        }
        
        $count = 0;
        foreach ($users as $user) {
            if ($user->role !== 'admin') {
                $user->update(['role' => 'admin']);
                $count++;
                $this->info("✓ {$user->name} ({$user->email}) défini comme administrateur");
            } else {
                $this->line("  {$user->name} ({$user->email}) est déjà administrateur");
            }
        }
        
        $this->info("\n{$count} utilisateur(s) mis à jour avec succès.");
        
        return 0;
    }
}



