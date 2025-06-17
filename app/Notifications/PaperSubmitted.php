<?php

namespace App\Notifications;

use App\Models\Paper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaperSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $paper;

    public function __construct(Paper $paper)
    {
        $this->paper = $paper;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Paper Submission: ' . $this->paper->title)
            ->line('A new paper has been submitted to ' . $this->paper->conference->title)
            ->action('View Paper', route('papers.show', $this->paper))
            ->line('Thank you for using our system!');
    }

    public function toArray($notifiable)
    {
        return [
            'paper_id' => $this->paper->id,
            'conference_id' => $this->paper->conference_id,
            'title' => $this->paper->title,
            'message' => 'New paper submitted: ' . $this->paper->title,
            'link' => route('papers.show', $this->paper),
        ];
    }
}
