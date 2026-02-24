<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorized by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_code' => 'required', 
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'gender' => 'required|integer', 
            'guardian_name' => 'required|string|max:255',
            'blood_group' => 'required',
            'any_known_allergies' => 'nullable|string', 
            'email' => 'nullable|email|unique:hospital_patients,email,NULL,id,delete_status,0',
            'phone_alternative' => 'nullable|string',
            'address' => 'nullable|string',
            'remark' => 'nullable|string',
            'marital_status' => 'nullable|integer',
            'age_year' => 'nullable|integer',
            'age_month' => 'nullable|integer',
            'patient_photo' => 'nullable|string',
        ];
    }
}
