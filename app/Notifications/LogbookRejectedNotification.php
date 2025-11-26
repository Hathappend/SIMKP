<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class LogbookRejectedNotification extends Notification
{
    use Queueable;

    public Logbook $logbook;

    /**
     * Create a new notification instance.
     */
    public function __construct(Logbook $logbook)
    {
        $this->logbook = $logbook;
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
        return [
            'title'   => 'Logbook Perlu Revisi ⚠️',
            'message' => 'Kegiatan "' . Str::limit($this->logbook->activity, 20) . '" ditolak. Catatan: "' . Str::limit($this->logbook->feedback, 30) . '"',
            'link'    => route('mahasiswa.logbook.index'),
            'icon'    => 'x-circle',
            'color'   => 'red',
        ];
    }
}
