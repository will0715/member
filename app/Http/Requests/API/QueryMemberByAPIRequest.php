<?php

namespace App\Http\Requests\API;

use App\Models\Member;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class QueryMemberByAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('view-member');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'gender' => 'in:male,female,others,unknown',
            'email' => 'email',
        ];
        
        return $rules;
    }
}
