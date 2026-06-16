<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RabProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'proposed_date',
        'total_budget',
        'status',
        'tor_file_path',
        'rab_number',
        'signature_path',
    ];

    protected $casts = [
        'proposed_date' => 'date',
        'total_budget' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(RabDetail::class);
    }

    public function verificationLogs()
    {
        return $this->hasMany(VerificationLog::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending_kaprodi', 'pending_wd', 'pending_dekan']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopePendingKaprodi($query)
    {
        return $query->where('status', 'pending_kaprodi');
    }

    public function scopePendingWd($query)
    {
        return $query->where('status', 'pending_wd');
    }

    public function scopePendingDekan($query)
    {
        return $query->where('status', 'pending_dekan');
    }
}
