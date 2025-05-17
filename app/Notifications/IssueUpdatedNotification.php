<?php

namespace App\Notifications;

use App\Models\Tenant\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueUpdatedNotification extends Notification
{
    //use Queueable;

    /**
     * Create a new notification instance.
     */
    private Issue $issue;
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
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
        try {
            $frontendUrl = tenant_url('frontend');
            return (new MailMessage)
                ->subject("Issue Updated: {$this->issue->title}")
                ->line("An issue you're subscribed to on {$frontendUrl} has been updated.")
                ->action('View Issue', "{$frontendUrl}/issues/{$this->issue->id}");
    
        } catch (\Throwable $e) {
            \Log::error('Error in IssueUpdateNotification@toMail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notifiable' => $notifiable,
            ]);
    
            // Optional: fail silently or throw again
            // return (new MailMessage)->line('An error occurred while sending the notification.');
            throw $e; // Or comment this line if you don't want the job to fail
        }
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
