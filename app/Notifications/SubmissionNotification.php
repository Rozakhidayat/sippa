<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubmissionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $submission;
    protected $messageStr;
    public function __construct($submission, $messageStr)
    {
        $this->submission = $submission;
        $this->messageStr = $messageStr;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
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
        $roleName = strtolower($notifiable->role->name ?? '');

        $routeName = match ($roleName) {
            'staff'                => 'pengajuan.show',
            'manajer ti'           => 'ti.pengajuan.show',
            'vp'                   => 'vp.approval.show',
            'svp'                  => 'svp.approval.show',
            'business partner'     => 'bp.pengajuan.show',
            'enterprise architect' => 'ea.pengajuan.show',
            default                => 'pengajuan.show',
        };
        return [
            'submission_id' => $this->submission->id,
            'title' => $this->submission->no_ticket ?? 'Update Pengajuan',
            'message' => $this->messageStr,
            'url'           => route($routeName, ['no_ticket' => $this->submission->no_ticket]),
        ];
    }
}
