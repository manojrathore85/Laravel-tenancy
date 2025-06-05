<?php

namespace App\Notifications;

use App\Models\Tenant\Comment;
use App\Models\Tenant\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueCommentedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private Issue $issue;
    private Comment $comment;
    private $recipientType;
    private $changes = [];
    public function __construct(Issue $issue, Comment $comment, $recipientType, array $changes = [])
    {
        $this->recipientType = $recipientType;
        $this->issue = $issue;
        $this->comment = $comment;
        $this->changes = $changes;
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
            $commentMode = $this->changes != [] ? 'updated' : 'added';
             switch ($this->recipientType) {
                case 'assignee':             
                        $title = $this->changes != [] ? 'You assign issue comment updated' : 'Your assigned issue has a comment';
                    
                    break;      
                case 'creator':             
                        $title = $this->changes != [] ? 'comment update on your issue' : 'comment on your issue'  ;
                    break;
                case 'commenter':
                        $title = $this->changes != [] ? 'You updated a comment on an issue' : 'You commented on an issue'  ;
                    break;    
                case 'updator':             
                        $title = 'You update a comment on an issue';    
                     break;
                case 'team':
                        $title = $this->changes != [] ?  'Your team updated comment on an issue' : 'Your team added a comment on an issue';            
                default:
                        $title = 'Comment Notification';
            }
            $issueUrl = $frontendUrl."/issues/".$this->issue->id;
            $subject = "(".$this->issue->project->code. ")|". $this->issue->summery. "|IMS|Comment ".$commentMode."|(Url:". $issueUrl.")";
            return (new MailMessage)
                 ->subject($subject)                
                ->view("emails.issueNotification", [
                    'subject' => $subject,
                    'title' => $title,
                    'issue' => $this->issue,
                    'comment' => $this->comment,
                    'url' => $frontendUrl,
                    'recipientType' => $this->recipientType,
                    'comment_changes'=> $this->changes
                ]);
    
        } catch (\Throwable $e) {
            \Log::error('Error in IssueCommentedNotification@toMail', [
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
