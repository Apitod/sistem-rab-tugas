<?php
namespace App\Events;
use App\Models\RabProposal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RabProposalApproved {
    use Dispatchable, SerializesModels;
    public function __construct(public RabProposal $proposal) {}
}
