<?php

namespace App\Http\Requests\API;

use App\Models\Promotion;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdatePromotionAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-promotion');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required|unique:promotions,code',
            'name' => 'required',
            'type' => 'required',
            'sequence' => 'required|integer',
            'activated_date_start' => 'required|date_format:Y-m-d',
            'activated_date_end' => 'required|date_format:Y-m-d',
            'activated_time_start' => 'required|date_format:H:i:s',
            'activated_time_end' => 'required|date_format:H:i:s',
            'trigger_condition' => 'required',
            'content' => 'required',
        ];
        
        return $rules;
    }
}
