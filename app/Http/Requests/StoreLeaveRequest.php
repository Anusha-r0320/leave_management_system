<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'start_date.after_or_equal' => 'Start Date cannot be earlier than the current date.',
            'end_date.after_or_equal' => 'End Date cannot be earlier than Start Date.',
            'leave_type_id.required' => 'Please select a Leave Type.',
            'start_date.required' => 'A Start Date is required.',
            'end_date.required' => 'An End Date is required.',
            'reason.required' => 'A reason for the leave must be provided.',
        ];
    }
}
