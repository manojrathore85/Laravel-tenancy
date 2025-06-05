<?php

namespace App\Notifications;

use App\Models\Tenant\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private Issue $issue;
    private $recipientType;
    public function __construct(Issue $issue, $recipientType)
    {
        $this->issue = $issue;
        $this->recipientType = $recipientType;
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
            switch ($this->recipientType) {
                case 'assignee':
                    $title = 'A New issue assigned to you';
                    break;
                case 'watcher':
                    $title = 'The project you are viewing an issue created';
                    break;
                case 'creator':
                    $title = 'A new issue has been created by you';
                    break;
                 
                case 'team':
                    $title = 'A new issue created in your team';
                    break;
                default:
                    $title = 'New Issue Notification';
                    break;
            }
            $issueUrl = $frontendUrl."/issues/".$this->issue->id;
            $subject = "(".$this->issue->project->code. ")|". $this->issue->summery. "|IMS|New Issue|(Url:". $issueUrl.")";
            
            return (new MailMessage)
                ->subject($subject)
                ->view("emails.issueNotification", [
                    'subject' => $subject,
                    'title' => $title,
                    'issue' => $this->issue,
                    'url' => $frontendUrl,
                    'recipientType' => $this->recipientType,
                ]);
    
        } catch (\Throwable $e) {
            \Log::error('Error in IssueCreateNotification@toMail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notifiable' => $notifiable,
            ]);      
            throw $e;
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
