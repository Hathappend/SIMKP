<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationStatusNotification extends Notification
{
    use Queueable;

    public Registration $registration;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
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
        $status = $this->registration->application_status;
        $divisionName = $this->registration->division->name;

        if ($status === 'approved') {
            $title = 'Pengajuan Diterima! 🎉';
            $message = "Selamat! Pengajuan magang Anda di divisi $divisionName telah disetujui.";
            $icon = 'check-circle';
            $color = 'green';
        } else {
            $title = 'Pengajuan Ditolak';
            $message = "Mohon maaf, pengajuan magang Anda belum dapat kami terima saat ini.";
            $icon = 'x-circle';
            $color = 'red';
        }

        return [
            'title'   => $title,
            'message' => $message,
            'link'    => route('mahasiswa.dashboard'),
            'icon'    => $icon,
            'color'   => $color,
        ];
    }
}
