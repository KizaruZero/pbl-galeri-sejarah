<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class PhotoLikes extends Notification
{
    use Queueable;

    protected $title;
    protected $reaction;
    protected $userId;
    protected $userName;

    public function __construct($title, $reaction, $user)
    {
        $this->title = $title;
        $this->reaction = $reaction;
        // If user is an object, get the name directly; if it's an ID, store it
        if (is_object($user)) {
            $this->userName = $user->name;
        } else {
            $this->userId = $user;
        }
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
        return (new MailMessage);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Use stored userName if available, otherwise get from database
        if (isset($this->userName)) {
            $userName = $this->userName;
        } else {
            $user = User::find($this->userId);
            $userName = $user ? $user->name : 'Unknown User';
        }

        return [
            'data' => 'Your Content Photo With Title ' . $this->title . ' has been ' . $this->reaction . ' by ' . $userName,
        ];
    }
}
