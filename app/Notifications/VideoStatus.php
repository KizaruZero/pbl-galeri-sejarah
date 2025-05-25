<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VideoStatus extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $status;
    protected $title;
    protected $note;
    public function __construct($status, $title, $note)
    {
        $this->status = $status;
        $this->title = $title;
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->status = $this->status == 'approved' ? 'Approved' : 'Rejected';
        if ($this->status == 'Approved') {
            return [
                'data' => 'Your Content Photo With Title ' . $this->title . ' has been ' . $this->status,
            ];
        } else if ($this->status == 'Rejected') {
            return [
                'data' => 'Your Content Video With Title ' . $this->title . ' has been ' . $this->status . '. Reason: ' . $this->note,
            ];
        }
    }
}
