<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Review Assignment: ' . $this->review->paper->title)
            ->line('You have been assigned to review a paper:')
            ->line('Title: ' . $this->review->paper->title)
            ->line('Conference: ' . $this->review->paper->conference->title)
            ->action('Begin Review', route('reviews.edit', $this->review))
            ->line('Please complete your review by the deadline.');
    }

    public function toArray($notifiable)
    {
        return [
            'review_id' => $this->review->id,
            'paper_id' => $this->review->paper_id,
            'conference_id' => $this->review->paper->conference_id,
            'title' => $this->review->paper->title,
            'message' => 'You have been assigned to review: ' . $this->review->paper->title,
            'link' => route('reviews.edit', $this->review),
        ];
    }
}
