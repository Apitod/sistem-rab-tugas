<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabDetail extends Model
{
    protected $fillable = [
        'rab_proposal_id',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function proposal()
    {
        return $this->belongsTo(RabProposal::class, 'rab_proposal_id');
    }

    public function getTotalPriceAttribute($value)
    {
        return $this->quantity * $this->unit_price;
    }
}
