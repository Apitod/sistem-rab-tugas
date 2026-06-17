<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class VerifyProposalRequest extends FormRequest {
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        $isDekan = auth()->user()->role === 'dekan';
        return [
            'action'     => 'required|in:verify,revisi,tolak,setujui',
            'notes'      => 'nullable|string|max:1000',
            'rab_number' => ($isDekan ? 'required' : 'nullable') . '|string|max:100',
            'signature'  => 'nullable|string',
        ];
    }
}
