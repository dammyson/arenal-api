<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArenaRewardCode extends Notification
{
    use Queueable;

    protected  $redeemCode;
    protected  $prizeName;

    /**
     * Create a new notification instance.
     */
    public function __construct($prizeName, $redeemCode)
    {
        $this->prizeName = $prizeName;
        $this->redeemCode = $redeemCode;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('gilbertgenye4@gmail.com', 'Arena OTP')
            ->greeting("Congratulations!")
            ->line("You have successfully redeemed your {$this->prizeName}. Use the code below as a proof of claim")
            ->line($this->redeemCode)
            ->line('Thank you for using our application!');;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
