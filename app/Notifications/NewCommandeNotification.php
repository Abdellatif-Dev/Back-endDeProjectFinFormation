<?php

namespace App\Notifications;

use App\Models\CommandesDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommandeNotification extends Notification
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
            'message' => 'Vous avez reçu une nouvelle commande.',
            'commande_id' => $this->detail->commande_id,
            'menu_id' => $this->detail->menu_id,
            'client_name' => $this->detail->commande->user->name,
            'date' => $this->detail->commande->date,
            'quantity' => $this->detail->quantity,
            'total_price' => $this->detail->total_price,
        ];
    }

    
}
