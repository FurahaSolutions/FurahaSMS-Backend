<?php

namespace Okotieno\SupportStaff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportStaffStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create support staff');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required',
            'staff_type' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email Field is required',
            'first_name.required' => 'First Name Field is required',
            'last_name.required' => 'Last Name Field is required',
            'date_of_birth.required' => 'Date of Birth Field is required',
        ];
    }
    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            'You are not authorised to create a support staff'
        );
    }
}
