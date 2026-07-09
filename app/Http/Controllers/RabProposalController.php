<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreRabProposalRequest;
use App\Models\RabProposal;
use App\Models\RabDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * Mendukung: update item existing, tambah item baru, item locked (tidak berubah).
     */
    public function resubmit(Request $request, string $id) {
        $proposal = RabProposal::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'revisi')
            ->firstOrFail();

        $totalBudget = 0;

        foreach ($request->items ?? [] as $itemData) {
            $hasId = !empty($itemData['id']);

            if ($hasId) {
                // ── Item existing: update ──────────────────────────────
                $detail = RabDetail::where('id', $itemData['id'])
                    ->where('rab_proposal_id', $proposal->id)
                    ->first();

                if ($detail) {
                    $qty        = max(1, (int)   ($itemData['quantity']   ?? $detail->quantity));
                    $unitPrice  = max(0, (float) ($itemData['unit_price'] ?? $detail->unit_price));
                    $totalPrice = $qty * $unitPrice;
                    $totalBudget += $totalPrice;

                    $detail->update([
                        'item_name'       => $itemData['item_name']  ?? $detail->item_name,
                        'quantity'        => $qty,
                        'unit'            => $itemData['unit']        ?? $detail->unit,
                        'unit_price'      => $unitPrice,
                        'total_price'     => $totalPrice,
                        'revision_flag'   => false,
                        'revision_reason' => null,
                    ]);
                }
            } else {
                // ── Item baru: create ──────────────────────────────────
                $itemName  = trim($itemData['item_name'] ?? '');
                if ($itemName === '') continue; // skip baris kosong

                $qty        = max(1, (int)   ($itemData['quantity']   ?? 1));
                $unitPrice  = max(0, (float) ($itemData['unit_price'] ?? 0));
                $totalPrice = $qty * $unitPrice;
                $totalBudget += $totalPrice;

                RabDetail::create([
                    'rab_proposal_id' => $proposal->id,
                    'item_name'       => $itemName,
                    'quantity'        => $qty,
                    'unit'            => trim($itemData['unit'] ?? ''),
                    'unit_price'      => $unitPrice,
                    'total_price'     => $totalPrice,
                    'revision_flag'   => false,
                    'revision_reason' => null,
                ]);
            }
        }

        // Hitung ulang total dari DB (lebih akurat, termasuk item yang tidak berubah)
        $realTotal = DB::table('rab_details')
            ->where('rab_proposal_id', $proposal->id)
            ->sum(DB::raw('quantity * unit_price'));

        $proposal->update([
            'total_budget' => $realTotal > 0 ? $realTotal : $totalBudget,
            'status'       => 'pending_kaprodi',
        ]);

        return redirect()
            ->route('pengusul.rab.show', $proposal->id)
            ->with('success', 'RAB berhasil diajukan ulang dan menunggu verifikasi Kaprodi.');
    }

    /**
     * Pengusul menghapus item yang bertanda revisi dari proposal berstatus 'revisi'.
     */
    public function deleteRevisionItem(Request $request, string $id, string $itemId) {
        $proposal = RabProposal::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'revisi')
            ->firstOrFail();

        $detail = RabDetail::where('id', $itemId)
            ->where('rab_proposal_id', $proposal->id)
            ->where('revision_flag', true) // hanya item yang ditandai revisi yang boleh dihapus
            ->firstOrFail();

        // Pastikan minimal masih ada 1 item setelah penghapusan
        $remainingCount = $proposal->details()->count();
        if ($remainingCount <= 1) {
            return back()->with('error', 'Tidak bisa menghapus item terakhir. RAB harus memiliki minimal 1 item.');
        }

        $detail->delete();

        // Hitung ulang total anggaran
        $newTotal = $proposal->details()->sum(\DB::raw('quantity * unit_price'));
        $proposal->update(['total_budget' => $newTotal]);

        return back()->with('success', 'Item berhasil dihapus.');
    }
}
