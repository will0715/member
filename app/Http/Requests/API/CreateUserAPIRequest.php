<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class CreateUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return User::$rules;
    }
}
