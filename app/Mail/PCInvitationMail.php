<?php

namespace App\Mail;

use App\Models\ProgramCommittee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PCInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pc;

    public function __construct(ProgramCommittee $pc)
    {
        $this->pc = $pc;
    }

    public function build()
    {
        return $this->markdown('emails.pc-invitation')
                    ->subject('Invitation to Join Program Committee - ' . $this->pc->conference->title);
    }
}
