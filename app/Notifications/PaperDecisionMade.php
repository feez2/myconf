<?php

namespace App\Notifications;

use App\Models\Paper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaperDecisionMade extends Notification implements ShouldQueue
{
    use Queueable;

    public $paper;

    public function __construct(Paper $paper)
    {
        $this->paper = $paper;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Decision on Your Paper: ' . $this->paper->title)
            ->line('A decision has been made on your paper:')
            ->line('Title: ' . $this->paper->title)
            ->line('Status: ' . $this->paper->status)
            ->line('Decision Notes: ' . $this->paper->decision_notes)
            ->action('View Paper', route('papers.show', $this->paper))
            ->line('Thank you for your submission!');
    }

    public function toArray($notifiable)
    {
        return [
            'paper_id' => $this->paper->id,
            'conference_id' => $this->paper->conference_id,
            'title' => $this->paper->title,
            'message' => 'Decision made on your paper: ' . $this->paper->title . ' - ' . $this->paper->status,
            'link' => route('papers.show', $this->paper),
        ];
    }
}
