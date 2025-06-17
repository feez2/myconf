<?php

namespace App\Events;

use App\Models\Paper;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaperSubmittedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paper;

    public function __construct(Paper $paper)
    {
        $this->paper = $paper;
    }
}
