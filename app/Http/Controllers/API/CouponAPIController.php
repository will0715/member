<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCouponAPIRequest;
use App\Http\Requests\API\UpdateCouponAPIRequest;
use App\Http\Resources\Coupon;
use App\Constants\CouponConstant;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Response;

class CouponAPIController extends AppBaseController
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * 顯示優惠券列表
     * GET|HEAD /coupons
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $coupons = $this->couponService->listCoupons($request);
        $coupons->load(CouponConstant::SIMPLE_COUPON_RELATIONS);

        $count = $this->couponService->couponsCount($request);

        return $this->sendResponseWithTotalCount(Coupon::collection($coupons), 'coupons retrieved successfully', $count);
    }

    /**
     * 儲存新創建的優惠券
     * POST /coupons
     *
     * @param CreateCouponAPIRequest $request
     * @return Response
     */
    public function store(CreateCouponAPIRequest $request)
    {
        $input = $request->all();

        $coupon = $this->couponService->newCoupon($input);

        return $this->sendResponse(new Coupon($coupon), 'coupon created successfully');
    }

    /**
     * 顯示指定的優惠券
     * GET|HEAD /coupons/{id}
     *
     * @param string $id
     * @return Response
     */
    public function show($id)
    {
        $coupon = $this->couponService->findCoupon($id);

        if (empty($coupon)) {
            return $this->sendError('coupon not found');
        }
        $coupon->load(CouponConstant::SIMPLE_COUPON_RELATIONS);

        return $this->sendResponse(new Coupon($coupon), 'coupon retrieved successfully');
    }

    /**
     * 更新指定的優惠券
     * PUT/PATCH /coupons/{id}
     *
     * @param string $id
     * @param UpdateCouponAPIRequest $request
     * @return Response
     */
    public function update($id, UpdateCouponAPIRequest $request)
    {
        $input = $request->all();

        $coupon = $this->couponService->findCoupon($id);

        if (empty($coupon)) {
            return $this->sendError('coupon not found');
        }

        $coupon = $this->couponService->updateCoupon($input, $id);

        return $this->sendResponse(new Coupon($coupon), 'coupon updated successfully');
    }

    /**
     * 刪除指定的優惠券
     * DELETE /coupons/{id}
     *
     * @param string $id
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $coupon = $this->couponService->findCoupon($id);

        if (empty($coupon)) {
            return $this->sendError('coupon not found');
        }

        $this->couponService->deleteCoupon($id);

        return $this->sendSuccess('coupon deleted successfully');
    }

    /**
     * 使用優惠券
     * POST /coupons/{id}/use
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function useCoupon($id, Request $request)
    {
        $usageData = $request->all();

        $result = $this->couponService->useCoupon($id, $usageData);

        if (!$result) {
            return $this->sendError('coupon cannot be used');
        }
        return $this->sendSuccess(new Coupon($result), 'coupon used successfully');
    }

    /**
     * 使用優惠券
     * POST /coupons/{id}/disable
     *
     * @param string $id
     * @param Request $request
     * @return Response
     */
    public function disableCoupon($id, Request $request)
    {
        $usageData = $request->all();

        $result = $this->couponService->disableCoupon($id, $usageData);

        return $this->sendSuccess(new Coupon($result), 'disable coupon successfully');
    }

    /**
     * 獲取會員的可用優惠券
     * GET /coupons/available/{memberId}
     *
     * @param string $memberId
     * @return Response
     */
    public function getMemberAvailableCoupons($memberId)
    {
        $coupons = $this->couponService->getAvailableCouponsForMember($memberId);

        return $this->sendResponse(Coupon::collection($coupons), 'coupon list retrieved successfully');
    }

    /**
     * 列出所有會員的優惠券
     * GET /coupons/all/{memberId}
     *
     * @param string $memberId
     * @return Response
     */
    public function getMemberAllCoupons($memberId)
    {
        $coupons = $this->couponService->getAllCouponsForMember($memberId);

        return $this->sendResponse(Coupon::collection($coupons), 'coupon list retrieved successfully');
    }

    public function generateTemporaryCode($id)
    {
        $code = $this->couponService->generateTemporaryCode($id);

        return $this->sendResponse(['code' => $code], 'coupon temporary code generated successfully');
    }

    public function useByTemporaryCode(Request $request)
    {
        $usageData = $request->all();

        $result = $this->couponService->useByTemporaryCode($usageData);

        if (!$result) {
            return $this->sendError('coupon cannot be used');
        }
        return $this->sendSuccess(new Coupon($result), 'coupon used successfully');
    }
}
