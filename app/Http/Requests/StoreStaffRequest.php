<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'designation_id' => 'required|integer', 
            'department_id' => 'required|integer', 
            'role_id' => 'required|integer', 
            'specialist_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospital_staffs,email,NULL,id,delete_status,0',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'date_join' => 'required|date',
            'gender' => 'required|integer',
            'staff_code' => 'required|unique:hospital_staffs,staff_code,NULL,id,delete_status,0',
        ];
    }
}
