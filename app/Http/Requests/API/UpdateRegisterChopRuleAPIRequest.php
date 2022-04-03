<?php

namespace App\Http\Requests\API;

use App\Models\RegisterChopRule;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdateRegisterChopRuleAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-register-chop-rule');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = RegisterChopRule::$rules;
        
        return $rules;
    }
}
