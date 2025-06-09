<?php

namespace App\Notifications;

use App\Models\CommandesDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeLivreeNotification extends Notification
{
    use Queueable;

    protected $detail;

    public function __construct(CommandesDetail $detail)
    {
        $this->detail = $detail;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Votre commande a été livrée !',
            'name_plate' => $this->detail->menu->name,
            'restarnat_name' => $this->detail->restaurant->name,
        ];
    }
}
