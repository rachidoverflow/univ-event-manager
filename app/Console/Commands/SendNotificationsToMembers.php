<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendNotificationsToMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:notify-members {--reunion= : L\'ID de la réunion} {--message= : Le message à envoyer}';
    protected $description = 'Envoie une notification par email aux membres (utilisateurs et invités) d\'une réunion';

    public function handle()
    {
        $reunionId = $this->option('reunion');
        $messageText = $this->option('message') ?: "Ceci est une notification importante concernant une prochaine réunion.";
        
        if ($reunionId) {
            $participants = \App\Models\Participant::where('reunion_id', $reunionId)->get();
            if ($participants->isEmpty()) {
                $this->warn("Aucun participant trouvé pour la réunion #{$reunionId}.");
                return;
            }
            $this->info("Envoi de notifications aux participants de la réunion #{$reunionId}...");
        } else {
            $this->warn("Aucun ID de réunion spécifié. Envoi à tous les utilisateurs par défaut...");
            $users = \App\Models\User::all();
            $participants = $users->map(fn($u) => (object)['user' => $u, 'guest_email' => null]);
        }

        $data = [
            'title' => 'Notification de Réunion',
            'message' => $messageText,
            'action_url' => url('/reunions' . ($reunionId ? "/$reunionId" : "")),
            'type' => 'info'
        ];

        foreach ($participants as $participant) {
            try {
                if (isset($participant->user) && $participant->user) {
                    // Pour les utilisateurs enregistrés
                    $participant->user->notify(new \App\Notifications\MeetingNotification($data));
                    $this->line("Notifié (Utilisateur) : {$participant->user->email}");
                } elseif (isset($participant->guest_email) && $participant->guest_email) {
                    // Pour les invités (guests)
                    \Illuminate\Support\Facades\Notification::route('mail', $participant->guest_email)
                        ->notify(new \App\Notifications\MeetingNotification($data));
                    $this->line("Notifié (Invité) : {$participant->guest_email}");
                }
            } catch (\Exception $e) {
                $email = $participant->user->email ?? $participant->guest_email ?? 'inconnu';
                $this->error("Échec pour {$email} : " . $e->getMessage());
            }
        }

        $this->info("Terminé !");
    }
}
