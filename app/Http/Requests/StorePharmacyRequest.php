<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePharmacyRequest extends FormRequest
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
            'pharmacy_category_id' => 'required|integer',  
            'title' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'form' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'schedule' => 'nullable|string|in:OTC,H,H1,X',
            'medicine_type' => 'nullable|string|in:allopathy,ayurveda,nutrition,psychology',
            'barcode' => 'nullable|string|max:100',
            'company_name' => 'required|string|max:255',              
            'unit' => 'required|string|max:50',              
            'quantity' => 'required|integer',              
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'generic_group_id' => 'nullable|integer|exists:hospital_settings_pharmacy,id',
            'is_generic' => 'nullable|boolean',
            'hsn_code' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'note' => 'nullable|string|max:5000',
        ];
    }
}
