<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
   use Queueable;

    protected $token;
    protected $domain;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $token, $domain )
    {
        $this->token  = $token;
        $this->domain = $domain;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url =  "http://$this->domain/recuperar-senha/$this->token" ;

        return (new MailMessage)
            ->level('success')
            ->subject('Bem-Vindo')
            ->greeting("Olá {$notifiable->name}!")
            ->line('Bem-vindo ao '.config('app.name').".\nEstamos muito felizes em ter você por aqui")
            ->line('Para definir a sua senha')
            ->action('Clique aqui', $url)
            ->line('Obrigado por usar a nossa aplicação!');
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
            //
        ];
    }
}
