<?php

namespace App\Http\Requests\API;

use App\Models\PickupCoupon;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class ConsumePickupCouponAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('edit-pickup-coupon');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'consumed_quantity' => 'required|numeric',
            'branch' => 'required',
        ];
    }
}
