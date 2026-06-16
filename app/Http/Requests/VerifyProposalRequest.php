<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class VerifyProposalRequest extends FormRequest {
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        return [
            'action' => 'required|in:verify,revisi,tolak',
            'notes' => 'nullable|string|max:1000',
            'rab_number' => 'required_if:action,verify|nullable|string|max:100',
            'signature' => 'required_if:action,verify|nullable|string',
        ];
    }
}
