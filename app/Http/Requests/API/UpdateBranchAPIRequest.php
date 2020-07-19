<?php

namespace App\Http\Requests\API;

use App\Models\Branch;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdateBranchAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-branch');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required|unique:branches,code,' . $this->route('branch'),
            'name' => 'required',
            'store_name' => 'required',
        ];
        
        return $rules;
    }
}
