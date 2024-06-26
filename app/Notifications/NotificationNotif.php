<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationNotif extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $id;
    public $url;
    public $title;
    public $description;
    public $color;
    public $icon;
    public $publish;

    private $offerData;

    public function __construct($offerData)
    {
        $this->offerData = $offerData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->offerData['id'],
            'url' => $this->offerData['url'],
            'title' => $this->offerData['title'],
            'message' => $this->offerData['message'],
            'color_icon' => $this->offerData['color_icon'],
            'icon' => $this->offerData['icon'],
            'publish' => $this->offerData['publish'],
        ];
    }
}
