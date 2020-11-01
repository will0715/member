<?php

namespace App\Http\Requests\API\Client;

use App\Models\Member;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdateMemberAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty($this->get('_member'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'phone' => [
                'required',
                Rule::unique('members')->ignore($this->get('_member')->id),
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|in:male,female,others,unknown',
            'email' => 'email',
            'password' => 'confirmed'
        ];
        
        return $rules;
    }
}
