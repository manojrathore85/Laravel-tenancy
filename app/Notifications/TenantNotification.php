<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantNotification extends Notification
{
    //use Queueable;

    /**
     * Create a new notification instance.
     */
    private $tenant;
     public function __construct( $tenant)
    {
        $this->tenant = $tenant;
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
              return (new MailMessage)
                ->subject('Your new app has been created')
                ->view("emails.tenantNotification", [
                    'tenant' => $this->tenant,
                    'user' =>  $notifiable,
                    'url' => config('app.tenant_protocol').'://'.$this->tenant->domains->first()->frontend_url,                 
                ]);
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
