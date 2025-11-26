<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class LogbookApprovedNotification extends Notification
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
            'title'   => 'Logbook Disetujui ✅',
            'message' => 'Kegiatan "' . Str::limit($this->logbook->activity, 20) . '" pada tanggal ' . \Carbon\Carbon::parse($this->logbook->date)->format('d M') . ' telah disetujui oleh pembimbing.',
            'link'    => route('mahasiswa.logbook.index'),
            'icon'    => 'check-circle',
            'color'   => 'green',
        ];
    }
}
