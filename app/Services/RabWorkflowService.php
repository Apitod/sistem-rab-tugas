<?php
namespace App\Services;
use App\Models\RabProposal;
use App\Models\VerificationLog;
use App\Events\RabProposalApproved;

class RabWorkflowService {
    public function __construct(private NotificationService $notif) {}

    public function verifyKaprodi(RabProposal $proposal, int $verifierId, string $notes = ''): void {
        $proposal->update(['status' => 'pending_wd']);
        VerificationLog::create(['rab_proposal_id'=>$proposal->id,'verifier_id'=>$verifierId,'status_checked'=>'verifikasi_ok','notes'=>$notes,'created_at'=>now()]);
        $this->notif->send($proposal->user_id, 'RAB Diverifikasi Kaprodi', "RAB '{$proposal->title}' telah diverifikasi Kaprodi dan diteruskan ke WD Keuangan.");
    }

    public function verifyWd(RabProposal $proposal, int $verifierId, string $notes = ''): void {
        $proposal->update(['status' => 'pending_dekan']);
        VerificationLog::create(['rab_proposal_id'=>$proposal->id,'verifier_id'=>$verifierId,'status_checked'=>'verifikasi_ok','notes'=>$notes,'created_at'=>now()]);
        $this->notif->send($proposal->user_id, 'RAB Diverifikasi WD Keuangan', "RAB '{$proposal->title}' telah diverifikasi WD Keuangan dan diteruskan ke Dekan.");
    }

    public function approveDekan(RabProposal $proposal, int $verifierId, string $rabNumber, string $signaturePath): void {
        $proposal->update(['status'=>'disetujui','rab_number'=>$rabNumber,'signature_path'=>$signaturePath]);
        VerificationLog::create(['rab_proposal_id'=>$proposal->id,'verifier_id'=>$verifierId,'status_checked'=>'verifikasi_ok','notes'=>'Disetujui Dekan','created_at'=>now()]);
        event(new RabProposalApproved($proposal));
        $this->notif->send($proposal->user_id, 'RAB Disetujui!', "RAB '{$proposal->title}' telah disetujui Dekan. Nomor RAB: {$rabNumber}.");
    }

    public function requestRevisi(RabProposal $proposal, int $verifierId, string $notes): void {
        $proposal->update(['status' => 'revisi']);
        VerificationLog::create(['rab_proposal_id'=>$proposal->id,'verifier_id'=>$verifierId,'status_checked'=>'revisi','notes'=>$notes,'created_at'=>now()]);
        $this->notif->send($proposal->user_id, 'RAB Perlu Direvisi', "RAB '{$proposal->title}' perlu direvisi. Catatan: {$notes}");
    }

    public function tolak(RabProposal $proposal, int $verifierId, string $notes): void {
        $proposal->update(['status' => 'ditolak']);
        VerificationLog::create(['rab_proposal_id'=>$proposal->id,'verifier_id'=>$verifierId,'status_checked'=>'ditolak','notes'=>$notes,'created_at'=>now()]);
        $this->notif->send($proposal->user_id, 'RAB Ditolak', "RAB '{$proposal->title}' ditolak. Alasan: {$notes}");
    }

    public function getEarlyWarningData(float $paguTahunan = 100000000): array {
        $realisasi = RabProposal::approved()->sum('total_budget');
        $persen = $paguTahunan > 0 ? ($realisasi / $paguTahunan) * 100 : 0;
        return ['realisasi'=>$realisasi,'pagu'=>$paguTahunan,'persen'=>round($persen,2),'warning'=>$persen>=80,'kritis'=>$persen>=100];
    }
}
