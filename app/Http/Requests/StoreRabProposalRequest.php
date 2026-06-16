<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreRabProposalRequest extends FormRequest {
    public function authorize(): bool { return auth()->check() && auth()->user()->role === 'pengusul'; }
    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'proposed_date' => 'required|date',
            'tor_file' => 'required|file|mimes:pdf|max:5120',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }
    public function messages(): array {
        return [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'proposed_date.required' => 'Tanggal pengajuan wajib diisi.',
            'tor_file.required' => 'Dokumen TOR wajib diunggah.',
            'tor_file.mimes' => 'Dokumen TOR harus berformat PDF.',
            'tor_file.max' => 'Ukuran file TOR maksimal 5MB.',
            'items.required' => 'Minimal satu item anggaran harus diisi.',
        ];
    }
}
