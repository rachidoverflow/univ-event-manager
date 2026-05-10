<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email=test@example.com}';
    protected $description = 'Envoie un email de test pour vérifier la configuration SMTP';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Tentative d'envoi d'un email de test à : {$email}...");

        try {
            \Illuminate\Support\Facades\Mail::raw("Ceci est un email de test envoyé depuis votre application Laravel Meeting Manager. Si vous lisez ceci, votre configuration SMTP locale fonctionne parfaitement !", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test SMTP Laravel - Meeting Manager');
            });

            $this->info("Succès ! L'email a été envoyé. Vérifiez votre serveur local (ex: Mailpit).");
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'envoi : " . $e->getMessage());
        }
    }
}
