<?php

namespace App\Http\Requests\API;

use App\Models\Chop;
use InfyOm\Generator\Request\APIRequest;

class ConsumeChopAPIRequest extends APIRequest
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
            'phone' => 'required',
            'branch_id' => 'required',
            'chops' => 'required|integer',
            'remark' => 'max:255',
            'transaction_no' => 'max:255'
        ];
    }
}