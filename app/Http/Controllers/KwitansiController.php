<?php
namespace App\Http\Controllers;

use App\Models\RabProposal;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KwitansiController extends Controller
{
    /**
     * Daftar semua RAB yang disetujui — TU bisa menerbitkan kwitansi.
     */
    public function index(Request $request)
    {
        $proposals = RabProposal::approved()
            ->with(['user', 'details'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('rab_number', 'like', '%' . $request->search . '%');
            })
            ->when($request->from, fn($q) => $q->whereDate('updated_at', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->whereDate('updated_at', '<=', $request->to))
            ->latest('updated_at')
            ->get();

        return view('kwitansi.index', compact('proposals'));
    }

    /**
     * Tampilkan kwitansi untuk TU — bisa melihat semua RAB yang disetujui.
     */
    public function show(string $id)
    {
        $proposal = RabProposal::approved()
            ->with(['user', 'details', 'verificationLogs.verifier'])
            ->findOrFail($id);

        return view('kwitansi.show', $this->buildViewData($proposal));
    }

    /**
     * Tampilkan kwitansi untuk Pengusul — hanya RAB milik user yang login.
     */
    public function showPengusul(string $id)
    {
        $proposal = RabProposal::approved()
            ->with(['user', 'details', 'verificationLogs.verifier'])
            ->where('user_id', auth()->id())   // pastikan hanya milik pengusul ini
            ->findOrFail($id);

        return view('kwitansi.show_pengusul', $this->buildViewData($proposal));
    }

    /**
     * TU menerbitkan kwitansi (bisa diperluas simpan nomor ke DB).
     */
    public function terbitkan(Request $request, string $id)
    {
        $proposal = RabProposal::approved()->findOrFail($id);

        return redirect()
            ->route('tu.kwitansi.show', $proposal->id)
            ->with('success', 'Kwitansi berhasil diterbitkan. Silakan cetak halaman ini.');
    }

    /**
     * TU mengirim notifikasi kwitansi ke pengusul.
     */
    public function kirim(Request $request, string $id)
    {
        $proposal = RabProposal::approved()
            ->with('user')
            ->findOrFail($id);

        $nomorKwitansi = 'KWT/' . str_replace('RAB/', '', $proposal->rab_number ?? $id);
        $kwitansiUrl = route('pengusul.kwitansi.show', $proposal->id);

        // Buat notifikasi ke pengusul
        Notification::create([
            'user_id'    => $proposal->user_id,
            'title'      => "Kwitansi RAB Tersedia — {$nomorKwitansi}",
            'message'    => "Kwitansi dengan nomor {$nomorKwitansi} untuk RAB \"{$proposal->title}\" telah diterbitkan oleh Tata Usaha. "
                          . "Dokumen ini merupakan bukti sah pencairan anggaran Anda. "
                          . "Lihat kwitansi Anda di: {$kwitansiUrl}",
            'is_read'    => false,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('tu.kwitansi.show', $proposal->id)
            ->with('success', "Kwitansi {$nomorKwitansi} berhasil dikirim ke {$proposal->user->name}. Pengusul akan mendapat notifikasi.");
    }

    // ── Helper ----------------------------------------------------------

    private function buildViewData(RabProposal $proposal): array
    {
        $dekanLog = $proposal->verificationLogs
            ->where('status_checked', 'verifikasi_ok')
            ->sortByDesc('created_at')
            ->first();

        $nomorKwitansi = 'KWT/' . str_replace('RAB/', '', $proposal->rab_number ?? '-');

        return compact('proposal', 'dekanLog', 'nomorKwitansi');
    }
}
