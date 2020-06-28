<?php

namespace App\Http\Requests\API;

use App\Models\Role;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdateRolePermissionsAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permissions.*' => 'string'
        ];
    }
}
