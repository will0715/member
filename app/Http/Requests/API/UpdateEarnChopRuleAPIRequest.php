<?php

namespace App\Http\Requests\API;

use App\Models\EarnChopRule;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdateEarnChopRuleAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-chops');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = EarnChopRule::$rules;
        
        return $rules;
    }
}
