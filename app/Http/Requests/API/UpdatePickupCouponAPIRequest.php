<?php

namespace App\Http\Requests\API;

use App\Models\PickupCoupon;
use InfyOm\Generator\Request\APIRequest;
use Auth;

class UpdatePickupCouponAPIRequest extends APIRequest
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
        $rules = [
            'product_name' => 'required',
            'product_no' => 'required',
            'quantity' => 'required',
            'consumed_quantity' => 'required',
            'price' => 'required',
        ];
        
        return $rules;
    }
}
