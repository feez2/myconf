<?php

namespace App\Events;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewRequestedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paper;
    public $reviewers;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Paper $paper, array $reviewers, ?string $message = null)
    {
        $this->paper = $paper;
        $this->reviewers = $reviewers;
        $this->message = $message;
    }
} 