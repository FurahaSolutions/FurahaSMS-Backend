<?php

namespace Okotieno\ELearning\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class DeleteLearningOutcomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('delete learning outcome');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
    public function messages()
    {
        return [

        ];
    }
    protected function failedAuthorization()
    {
        throw new AuthorizationException(
            'You are not authorised to delete a Learning Outcome'
        );
    }
}
