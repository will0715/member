<?php

namespace App\Http\Requests\API\Client;

use App\Models\Member;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class ResetPasswordAPIRequest extends APIRequest
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
        return [
            'token' => 'required',
            'new_password' => 'required',
        ];
    }
}
