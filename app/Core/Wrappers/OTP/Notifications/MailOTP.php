<?php

namespace App\Core\Wrappers\OTP\Notifications;

use App\Core\Channels\ValueFirstChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailOTP extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $email_code;
    protected $phone_code;
    protected $type;
    public function __construct($payload)
    {
        $this->payload = $payload;
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
        $messageArray = explode(PHP_EOL, $this->payload['message']);

        $mailInstance = (new MailMessage)
                    ->subject($this->payload['title']);
                foreach($messageArray as $line){
                    $mailInstance->line($line);
                }

        return $mailInstance;
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function toValueFirst($notifiable): string
    {
        return "Dear Customer, Your OTP is $this->phone_code please use this to complete phone verification. Please do not share your OTP with others for own financial security.";
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
