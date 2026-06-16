<?php
namespace App\Http\Controllers;
use App\Models\RabProposal;
use Illuminate\Http\Request;

class ReportController extends Controller {
    public function index(Request $request) {
        $proposals = RabProposal::approved()
            ->with(['user','details'])
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest()->get();
        return view('report.index', compact('proposals'));
    }
    public function exportPdf(Request $request) {
        // TODO: implement dompdf setelah package install
        return response('PDF export belum diimplementasi', 501);
    }
    public function exportExcel(Request $request) {
        // TODO: implement maatwebsite/excel
        return response('Excel export belum diimplementasi', 501);
    }
}
