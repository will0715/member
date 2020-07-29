<?php

namespace App\Http\Requests\API;

use App\Models\Customer;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdateCustomerAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('super-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'status' => 'integer',
            'expired_at' => 'date'
        ];
    }
}
