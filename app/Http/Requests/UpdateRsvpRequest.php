<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRsvpRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return $this->rsvp->short_id == $this->short_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'short_id' => 'required|bail|string|size:4|exists:rsvps,short_id',
            'will_attend' => 'required|boolean',
            'number_attending' => 'required|numeric'
        ];
    }
}
