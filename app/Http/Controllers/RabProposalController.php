<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreRabProposalRequest;
use App\Models\RabProposal;
use App\Models\RabDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RabProposalController extends Controller {

    public function index() {
        $proposals = RabProposal::where('user_id', auth()->id())->latest()->get();
        return view('rab.index', compact('proposals'));
    }

    public function create() {
        return view('rab.create');
    }

    public function store(StoreRabProposalRequest $request) {
        $torPath = $request->file('tor_file')->store('tor', 'public');
        $proposal = RabProposal::create([
            'user_id'       => auth()->id(),
            'title'         => $request->title,
            'proposed_date' => $request->proposed_date,
            'tor_file_path' => $torPath,
            'status'        => 'pending_kaprodi',
        ]);
        $totalBudget = 0;
        foreach ($request->items as $item) {
            $totalPrice   = $item['quantity'] * $item['unit_price'];
            $totalBudget += $totalPrice;
            RabDetail::create([
                'rab_proposal_id' => $proposal->id,
                'item_name'       => $item['item_name'],
                'quantity'        => $item['quantity'],
                'unit'            => $item['unit'],
                'unit_price'      => $item['unit_price'],
                'total_price'     => $totalPrice,
            ]);
        }
        $proposal->update(['total_budget' => $totalBudget]);
        return redirect()->route('pengusul.rab.index')->with('success', 'RAB berhasil diajukan!');
    }

    public function show(string $id) {
        $proposal = RabProposal::with(['details','verificationLogs.verifier'])->findOrFail($id);
        return view('rab.show', compact('proposal'));
    }

    /**
     * Pengusul mengajukan ulang RAB yang berstatus 'revisi'.
     * Reset semua flag revisi per-item, lalu kembalikan ke pending_kaprodi.
     */
    public function resubmit(Request $request, string $id) {
        $proposal = RabProposal::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'revisi')
            ->firstOrFail();

        // Reset semua flag revisi per-item
        $proposal->details()->update([
            'revision_flag'   => false,
            'revision_reason' => null,
        ]);

        // Kembalikan ke antrian Kaprodi
        $proposal->update(['status' => 'pending_kaprodi']);

        return redirect()
            ->route('pengusul.rab.show', $proposal->id)
            ->with('success', 'RAB berhasil diajukan ulang dan menunggu verifikasi Kaprodi.');
    }
}
