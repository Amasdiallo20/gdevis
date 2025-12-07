<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserAsAdmin extends Command
{
    protected $signature = 'user:set-admin {email}';
    protected $description = 'Définir un utilisateur comme administrateur';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utilisateur avec l'email '{$email}' introuvable.");
            return 1;
        }
        
        $user->update(['role' => 'admin']);
        
        $this->info("L'utilisateur '{$user->name}' ({$user->email}) a été défini comme administrateur.");
        
        return 0;
    }
}





