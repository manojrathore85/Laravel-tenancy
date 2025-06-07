<?php

namespace App\Notifications;

use App\Models\Tenant\Project;
use App\Models\Tenant\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectAssignedNotification extends Notification
{
    //use Queueable;

    /**
     * Create a new notification instance.
     */
    private Project $project;
    private $assignedBy;
    private $roleName;
    public function __construct(Project $project, $assignedBy, $roleName)
    {
        $this->project = $project;
        $this->assignedBy = $assignedBy;
        $this->roleName = $roleName;
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
                ->subject('You have assigne a new project')
                ->view("emails.projectAssignedNotification", [
                    'project' => $this->project,
                    'user' =>  $notifiable,
                    'roleName' => $this->roleName,
                    'assignedBy' => $this->assignedBy,
                    'lead' => $this->project->lead,
                    'url' => tenant_url('frontend'),                 
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
