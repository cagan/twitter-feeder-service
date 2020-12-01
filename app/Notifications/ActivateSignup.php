<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivateSignup extends Notification
{
    use Queueable;

    private User $user;

    private string $activationCode;

    public function __construct(User $user, string $activationCode)
    {
        $this->user = $user;
        $this->activationCode = $activationCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        // Add nexmo if you have nexmo credentials
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url("/api/auth/register/activate/{$notifiable->id}/{$notifiable->activation_token}");

        return (new MailMessage())->markdown(
            'signupactivation',
            [
                'user' => $this->user,
                'url' => $url,
                'activation_code' => $this->activationCode,
            ]
        );
    }

    public function toNexmo($notifiable)
    {
//        $basic = new Basic('c527aecd', 'xQ1MAcvMj3yKP3nT');
//        $client = new Client($basic);
//
//        $message = $client->message()->send(
//            [
//                'to' => '905434816161',
//                'from' => 'Vonage APIs',
//                'text' => 'Hello from Vonage SMS API',
//            ]
//        );
//
//        return (new NexmoMessage())->content('Your register has been received');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
