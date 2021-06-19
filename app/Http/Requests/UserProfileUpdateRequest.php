<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update user profile');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required_without_all:last_name,middle_name,other_names,date_of_birth,religion_id,gender_id,email,phone,profile_pic_id',
            'last_name' => 'required_without_all:first_name,middle_name,other_names,date_of_birth,religion_id,gender_id,email,phone,profile_pic_id',
            'middle_name' => 'required_without_all:first_name,last_name,other_names,date_of_birth,religion_id,gender_id,email,phone,profile_pic_id',
            'other_names' => 'required_without_all:first_name,last_name,middle_name,date_of_birth,religion_id,gender_id,email,phone,profile_pic_id',
            'date_of_birth' => 'date_format:Y-m-d|required_without_all:first_name,last_name,middle_name,other_names,religion_id,gender_id,email,phone,profile_pic_id',
            'religion_id' => 'required_without_all:first_name,last_name,middle_name,other_names,date_of_birth,,gender_id,email,phone,profile_pic_id',
            'gender_id' => 'required_without_all:first_name,last_name,middle_name,other_names,date_of_birth,religion_id,email,phone,profile_pic_id',
            'email' => 'email|required_without_all:first_name,last_name,middle_name,other_names,date_of_birth,religion_id,gender_id,phone,profile_pic_id',
            'phone' => 'required_without_all:first_name,last_name,middle_name,other_names,date_of_birth,religion_id,gender_id,email,profile_pic_id'
        ];
    }
}
