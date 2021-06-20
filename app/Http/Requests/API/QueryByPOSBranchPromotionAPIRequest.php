<?php

namespace App\Http\Requests\API;

use App\Models\Promotion;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class QueryByPOSBranchPromotionAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('view-promotion');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'branch' => 'required',
        ];
    }
}
