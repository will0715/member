<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreatePickupCouponAPIRequest;
use App\Http\Requests\API\UpdatePickupCouponAPIRequest;
use App\Http\Requests\API\ConsumePickupCouponAPIRequest;
use App\Http\Requests\API\GiveToPickupCouponAPIRequest;
use App\Http\Requests\API\QueryByBranchPickupCouponAPIRequest;
use App\Http\Resources\PickupCoupon;
use App\Services\PickupCouponService;
use Illuminate\Http\Request;
use Response;
use Log;
use DB;

/**
 * Class PickupCouponAPIController
 * @package App\Http\Controllers
 */

class PickupCouponAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->pickupCouponService = app(PickupCouponService::class);
    }

    /**
     * Display a listing of the PickupCoupon.
     * GET|HEAD /pickupCoupons
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $pickupCoupons = $this->pickupCouponService->listPickupCoupons($request);
        return $this->sendResponse(PickupCoupon::collection($pickupCoupons), 'Pickup coupons retrieved successfully');
    }

    /**
     * Store a newly created PickupCoupon in storage.
     * POST /pickupCoupons
     *
     * @param CreatePickupCouponAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePickupCouponAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $pickupCoupon = $this->pickupCouponService->newPickupCoupon($input);
            DB::commit();

            return $this->sendResponse(new PickupCoupon($pickupCoupon), 'Pickup coupon saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Display the specified PickupCoupon.
     * GET|HEAD /pickupCoupons/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pickupCoupon = $this->pickupCouponService->findPickupCoupon($id);
        
        return $this->sendResponse(new PickupCoupon($pickupCoupon), 'Pickup coupon retrieved successfully');
    }

    /**
     * Update the specified PickupCoupon in storage.
     * PUT/PATCH /pickupCoupons/{id}
     *
     * @param int $id
     * @param UpdatePickupCouponAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePickupCouponAPIRequest $request)
    {
        $input = $request->all();

        $pickupCoupon = $this->pickupCouponService->updatePickupCoupon($input, $id);
        return $this->sendResponse(new PickupCoupon($pickupCoupon), 'Pickup coupon updated successfully');
    }

    /**
     * Remove the specified PickupCoupon from storage.
     * DELETE /pickupCoupons/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pickupCoupon = $this->pickupCouponService->deletePickupCoupon($id);
        return $this->sendSuccess('PickupCoupon deleted successfully');
    }

    public function consume($code, ConsumePickupCouponAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $pickupCoupon = $this->pickupCouponService->consumePickupCoupon($input, $code);
            DB::commit();

            return $this->sendResponse(new PickupCoupon($pickupCoupon), 'pickup coupon consume successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function giveTo($id, GiveToPickupCouponAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $newPickupCoupon = $this->pickupCouponService->giveTo($input, $id);
            DB::commit();

            return $this->sendResponse(new PickupCoupon($newPickupCoupon), 'give pickup coupon successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function queryByBranch(QueryByBranchPickupCouponAPIRequest $request)
    {
        $pickupCoupons = $this->pickupCouponService->listByBranch($request);
        return $this->sendResponse(PickupCoupon::collection($pickupCoupons), 'Pickup coupons retrieved successfully');
    }
}
