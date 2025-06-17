<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProceedingsStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $paper;
    public $approved;
    public $comments;

    public function __construct($paper, $approved, $comments = null)
    {
        $this->paper = $paper;
        $this->approved = $approved;
        $this->comments = $comments;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->approved) {
            return (new MailMessage)
                ->subject('Your Paper Has Been Approved for Proceedings')
                ->line("Your paper '{$this->paper->title}' has been approved for inclusion in the conference proceedings.")
                ->action('View Paper', url("/papers/{$this->paper->id}"))
                ->line('Thank you for your contribution!');
        } else {
            return (new MailMessage)
                ->subject('Your Paper Needs Revisions for Proceedings')
                ->line("Your paper '{$this->paper->title}' requires revisions before it can be included in the conference proceedings.")
                ->line("Comments: {$this->comments}")
                ->action('View Paper', url("/papers/{$this->paper->id}"))
                ->line('Please address the comments and resubmit your camera-ready version.');
        }
    }

    public function toArray($notifiable)
    {
        return [
            'paper_id' => $this->paper->id,
            'title' => $this->approved
                ? "Paper approved for proceedings: {$this->paper->title}"
                : "Paper requires revisions for proceedings: {$this->paper->title}",
            'message' => $this->approved
                ? "Your paper has been approved for inclusion in the conference proceedings."
                : "Your paper requires revisions before it can be included in the proceedings. Comments: {$this->comments}",
            'url' => "/papers/{$this->paper->id}",
        ];
    }
}
