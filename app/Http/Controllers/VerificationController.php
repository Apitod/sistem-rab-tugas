<?php
namespace App\Http\Controllers;
use App\Http\Requests\VerifyProposalRequest;
use App\Models\RabDetail;
use App\Models\RabProposal;
use App\Services\RabWorkflowService;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller {
    public function __construct(private RabWorkflowService $workflow) {}

    public function indexKaprodi() {
        $proposals = RabProposal::pendingKaprodi()->with('user')->latest()->get();
        $ewData = $this->workflow->getEarlyWarningData();
        return view('dashboard.kaprodi', compact('proposals','ewData'));
    }
    public function indexWd() {
        $proposals = RabProposal::pendingWd()->with('user')->latest()->get();
        return view('dashboard.wd', compact('proposals'));
    }
    public function indexDekan() {
        $proposals = RabProposal::pendingDekan()->with('user')->latest()->get();
        return view('dashboard.dekan', compact('proposals'));
    }

    public function verify(VerifyProposalRequest $request, string $id) {
        $proposal   = RabProposal::findOrFail($id);
        $role       = auth()->user()->role;
        $verifierId = auth()->id();
        $notes      = $request->notes ?? '';

        // ── Revisi per-item (Kaprodi) ────────────────────────────────────
        if ($request->action === 'revisi') {
            $revisionItems = $request->revision_items ?? [];
            $revisionCount = count($revisionItems);

            // Tandai setiap item yang dicentang Kaprodi
            foreach ($revisionItems as $item) {
                RabDetail::where('id', $item['id'])
                    ->where('rab_proposal_id', $proposal->id) // keamanan: pastikan item milik proposal ini
                    ->update([
                        'revision_flag'   => true,
                        'revision_reason' => $item['reason'],
                    ]);
            }

            // Buat catatan global yang informatif
            $globalNote = "{$revisionCount} item perlu direvisi." . ($notes ? " Catatan: {$notes}" : '');
            $this->workflow->requestRevisi($proposal, $verifierId, $globalNote);

            return back()->with('success', "RAB dikembalikan untuk direvisi. {$revisionCount} item ditandai.");
        }

        // ── Tolak ────────────────────────────────────────────────────────
        if ($request->action === 'tolak') {
            $this->workflow->tolak($proposal, $verifierId, $notes);
            return back()->with('success', 'RAB ditolak.');
        }

        // ── Verifikasi / Setujui ─────────────────────────────────────────
        if ($role === 'kaprodi') {
            $this->workflow->verifyKaprodi($proposal, $verifierId, $notes);
        } elseif ($role === 'wd_keuangan') {
            $this->workflow->verifyWd($proposal, $verifierId, $notes);
        } elseif ($role === 'dekan') {
            $signaturePath = '';
            if ($request->signature) {
                $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i','', $request->signature));
                $filename = 'signatures/sig_'.$id.'_'.time().'.png';
                Storage::disk('public')->put($filename, $imgData);
                $signaturePath = $filename;
            }
            try {
                $this->workflow->approveDekan($proposal, $verifierId, $signaturePath);
            } catch (\Throwable $e) {
                return back()->with('error', 'Gagal menyetujui RAB: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Verifikasi berhasil.');
    }
}
