<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
        $employeeId = $this->route('employee')->id;
        
        return [
            'employee_id' => 'required|string|max:255|unique:employees,employee_id,' . $employeeId,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email,' . $employeeId,
            'phone' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'location_id' => 'nullable|exists:locations,id',
            'anydesk_id' => 'nullable|string|max:50',
            'anydesk_password' => 'nullable|string|max:100',
            'login_username' => 'nullable|string|max:255',
            'login_password' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
