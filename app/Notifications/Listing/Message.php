<?php

declare(strict_types=1);

namespace App\Notifications\Listing;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Message extends Notification
{
    use Queueable;

    /** @var string */
    public string $senderName;

    /** @var string */
    public string $senderEmail;

    /** @var string */
    public string $listingTitle;

    /** @var string */
    public string $text;

    /**
     * @param string $senderName
     * @param string $senderEmail
     * @param string $listingTitle
     * @param string $text
     */
    public function __construct(string $senderName, string $senderEmail, string $listingTitle, string $text)
    {
        $this->senderName   = $senderName;
        $this->senderEmail  = $senderEmail;
        $this->listingTitle = $listingTitle;
        $this->text         = $text;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('New message about your listing')
            ->line($this->senderName . ' (' . $this->senderEmail . ') has sent you a message about ' . $this->listingTitle . '.')
            ->line($this->text)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
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
