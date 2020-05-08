<?php

namespace App\Http\Requests\API;

use App\Models\Member;
use InfyOm\Generator\Request\APIRequest;

class UpdateMemberAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'phone' => 'required|unique:App\Models\Member,phone,' . $this->route('member'),
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|in:male,female,others,unknown',
            'email' => 'email',
        ];
        
        return $rules;
    }
}
