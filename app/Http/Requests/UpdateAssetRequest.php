<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
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
        $assetId = $this->route('asset')->id;
        
        return [
            'asset_tag' => 'required|string|max:255|unique:assets,asset_tag,' . $assetId,
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'location_id' => 'nullable|exists:locations,id',
            'date_received' => 'nullable|date',
            'delivery_order_number' => 'nullable|string|max:255',
            'warranty_months' => 'nullable|integer|min:0',
            'status' => 'required|in:Available,Assigned,Maintenance,Retired,Missing',
            'notes' => 'nullable|string',
        ];
    }
}
