<?php

namespace App\Notifications;

use App\Models\Conference;
use App\Models\ProgramCommittee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewerInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $conference;
    protected $message;
    protected $pc;

    /**
     * Create a new notification instance.
     */
    public function __construct(Conference $conference, ?string $message = null, ProgramCommittee $pc)
    {
        $this->conference = $conference;
        $this->message = $message;
        $this->pc = $pc;
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
        $mail = (new MailMessage)
            ->subject("Reviewer Invitation: {$this->conference->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been invited to join the Program Committee for {$this->conference->title} as a reviewer.");

        if ($this->message) {
            $mail->line($this->message);
        }

        return $mail
            ->action('Accept Invitation', route('pc-invitations.accept', $this->pc))
            ->action('Decline Invitation', route('pc-invitations.reject', $this->pc), 'red')
            ->line('Please respond to this invitation at your earliest convenience.')
            ->line('If you have any questions, please contact the conference chair.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'conference_id' => $this->conference->id,
            'conference_title' => $this->conference->title,
            'message' => $this->message,
            'type' => 'reviewer_invitation',
            'pc_id' => $this->pc->id
        ];
    }
} 