<?php

namespace App\Http\Requests\API;

use App\Models\RankExpiredSetting;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdateRankUpgradeSettingAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-rank');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'is_active' => 'required|boolean',
        ];

        return $rules;
    }
}
