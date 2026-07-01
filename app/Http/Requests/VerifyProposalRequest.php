<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class VerifyProposalRequest extends FormRequest {
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        $isDekan   = auth()->user()->role === 'dekan';
        $isRevisi  = $this->input('action') === 'revisi';
        return [
            'action'                  => 'required|in:verify,revisi,tolak,setujui',
            'notes'                   => 'nullable|string|max:1000',
            'rab_number'              => ($isDekan ? 'required' : 'nullable') . '|string|max:100',
            'signature'               => 'nullable|string',
            // Validasi revisi per-item (wajib saat action=revisi)
            'revision_items'          => ($isRevisi ? 'required' : 'nullable') . '|array|min:1',
            'revision_items.*.id'     => ($isRevisi ? 'required' : 'nullable') . '|integer|exists:rab_details,id',
            'revision_items.*.reason' => ($isRevisi ? 'required' : 'nullable') . '|string|max:500',
        ];
    }
    public function messages(): array {
        return [
            'revision_items.required'          => 'Pilih minimal 1 item yang perlu direvisi.',
            'revision_items.min'               => 'Pilih minimal 1 item yang perlu direvisi.',
            'revision_items.*.id.required'     => 'ID item tidak valid.',
            'revision_items.*.id.exists'       => 'Item tidak ditemukan.',
            'revision_items.*.reason.required' => 'Alasan revisi wajib diisi untuk setiap item yang ditandai.',
        ];
    }
}
