<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Driver;

class NewDriverCreated extends Notification
{
    use Queueable;

    protected $driver;

    /**
     * Create a new notification instance.
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // you can add 'mail' if you want to send email too
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Driver Submitted',
            'message' => "A new driver named {$this->driver->name} has been submitted by {$this->driver->company_name}.",
            'driver_id' => $this->driver->id,
            'partner_id' => $this->driver->partner_id,
            'submitted_at' => now(),
        ];
    }

    /**
     * (Optional) Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Driver Submitted')
            ->greeting('Hello Admin,')
            ->line("A new driver named {$this->driver->name} has been submitted by {$this->driver->company_name}.")
            ->action('View Driver', url('/admin/drivers/' . $this->driver->id)) // Adjust URL as needed
            ->line('Thank you for using ARKILA!');
    }
}
