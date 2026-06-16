<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'rab_proposal_id',
        'verifier_id',
        'status_checked',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(RabProposal::class, 'rab_proposal_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
