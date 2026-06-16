<?php
namespace App\Listeners;
use App\Events\RabProposalApproved;
use App\Models\Asset;

class SyncAssetsOnApproval {
    public function handle(RabProposalApproved $event): void {
        $proposal = $event->proposal;
        foreach ($proposal->details as $detail) {
            Asset::create([
                'rab_proposal_id' => $proposal->id,
                'item_name' => $detail->item_name,
                'quantity' => $detail->quantity,
                'unit' => $detail->unit,
                'price' => $detail->unit_price,
                'synced_at' => now(),
            ]);
        }
    }
}
