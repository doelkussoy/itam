<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfflineIpNotification extends Notification
{
    use Queueable;

    public $offlineIps;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $offlineIps)
    {
        $this->offlineIps = $offlineIps;
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
        $mailMessage = (new MailMessage)
            ->subject('Peringatan: IP Address Offline')
            ->greeting('Halo,')
            ->line('Sistem mendeteksi ada '.count($this->offlineIps).' IP Address yang saat ini tidak dapat dihubungi (Offline).');

        foreach ($this->offlineIps as $ip) {
            $details = 'IP: '.$ip['ip_address'];
            if (! empty($ip['name'])) {
                $details .= ' - '.$ip['name'];
            }
            $mailMessage->line($details);
        }

        return $mailMessage
            ->action('Lihat Semua IP', url('/ips'))
            ->line('Silakan periksa perangkat terkait.');
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
