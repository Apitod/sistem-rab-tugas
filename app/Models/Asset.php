<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'rab_proposal_id',
        'item_name',
        'quantity',
        'unit',
        'price',
        'synced_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'synced_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(RabProposal::class, 'rab_proposal_id');
    }
}
