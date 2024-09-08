<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCouponGroupAPIRequest;
use App\Http\Requests\API\UpdateCouponGroupAPIRequest;
use App\Http\Resources\CouponGroup;
use App\Http\Resources\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Response;

class CouponGroupAPIController extends AppBaseController
{
    private $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * 顯示優惠券組列表
     * GET|HEAD /coupon-groups
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $couponGroups = $this->couponService->listCouponGroups($request);

        return $this->sendResponse(CouponGroup::collection($couponGroups), '優惠券組列表獲取成功');
    }

    /**
     * 儲存新創建的優惠券組
     * POST /coupon-groups
     *
     * @param CreateCouponGroupAPIRequest $request
     * @return Response
     */
    public function store(CreateCouponGroupAPIRequest $request)
    {
        $input = $request->all();

        $couponGroup = $this->couponService->newCouponGroup($input);

        return $this->sendResponse(new CouponGroup($couponGroup), '優惠券組創建成功');
    }

    /**
     * 顯示指定的優惠券組
     * GET|HEAD /coupon-groups/{id}
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $couponGroup = $this->couponService->findCouponGroup($id);

        if (empty($couponGroup)) {
            return $this->sendError('優惠券組不存在');
        }

        return $this->sendResponse(new CouponGroup($couponGroup), '優惠券組詳情獲取成功');
    }

    /**
     * 更新指定的優惠券組
     * PUT/PATCH /coupon-groups/{id}
     *
     * @param int $id
     * @param UpdateCouponGroupAPIRequest $request
     * @return Response
     */
    public function update($id, UpdateCouponGroupAPIRequest $request)
    {
        $input = $request->all();

        $couponGroup = $this->couponService->findCouponGroup($id);

        if (empty($couponGroup)) {
            return $this->sendError('優惠券組不存在');
        }

        $couponGroup = $this->couponService->updateCouponGroup($input, $id);

        return $this->sendResponse(new CouponGroup($couponGroup), '優惠券組更新成功');
    }

    /**
     * 刪除指定的優惠券組
     * DELETE /coupon-groups/{id}
     *
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $couponGroup = $this->couponService->findCouponGroup($id);

        if (empty($couponGroup)) {
            return $this->sendError('優惠券組不存在');
        }

        $this->couponService->deleteCouponGroup($id);

        return $this->sendSuccess('優惠券組刪除成功');
    }

    /**
     * 發放優惠券給指定會員
     * POST /coupon-groups/{id}/issue
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function issueCoupons($id, Request $request)
    {
        $memberIds = collect($request->input('member_ids', []));
        $rankId = collect($request->input('rank_id', []));
        $quantity = $request->input('quantity', 1);

        $issuedCoupons = $this->couponService->issueCouponGroupToMembers($id, $memberIds, $rankId, $quantity);

        return $this->sendResponse(Coupon::collection($issuedCoupons), '優惠券發放成功');
    }
}
