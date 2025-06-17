<?php

namespace App\Mail;

use App\Models\ProgramCommittee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewerInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pc;

    public function __construct(ProgramCommittee $pc)
    {
        $this->pc = $pc;
    }

    public function build()
    {
        return $this->markdown('emails.reviewer-invitation')
                    ->subject("Reviewer Invitation: {$this->pc->conference->title}");
    }
} 