<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRequested extends Notification
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de rendez-vous - RMI Class')
            ->line('Une nouvelle demande de rendez-vous a été reçue.')
            ->line('Utilisateur : ' . $this->appointment->full_name)
            ->line('Sujet : ' . $this->appointment->subject)
            ->line('Date souhaitée : ' . $this->appointment->formatted_date)
            ->action('Voir la demande', url('/admin/appointments/' . $this->appointment->id))
            ->line('Merci de traiter cette demande dans les plus brefs délais.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'user_name' => $this->appointment->full_name,
            'subject' => $this->appointment->subject,
            'appointment_date' => $this->appointment->appointment_date,
        ];
    }
}