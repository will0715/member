<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreatePromotionAPIRequest;
use App\Http\Requests\API\UpdatePromotionAPIRequest;
use App\Http\Requests\API\QueryByPOSBranchPromotionAPIRequest;
use App\Http\Resources\Promotion;
use App\Services\PromotionService;
use App\Constants\PromotionConstant;
use Illuminate\Http\Request;
use Response;
use Log;
use DB;

/**
 * Class PromotionAPIController
 * @package App\Http\Controllers
 */

class PromotionAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->promotionService = app(PromotionService::class);
    }

    /**
     * Display a listing of the Promotion.
     * GET|HEAD /promotions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $promotions = $this->promotionService->listPromotions($request);
        $promotions->load(PromotionConstant::SIMPLE_PROMOTION_RELATIONS);

        return $this->sendResponse(Promotion::collection($promotions), 'Promotions retrieved successfully');
    }

    /**
     * Store a newly created Promotion in storage.
     * POST /promotions
     *
     * @param CreatePromotionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePromotionAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $promotion = $this->promotionService->newPromotion($input);
            DB::commit();

            return $this->sendResponse(new Promotion($promotion), 'Promotion saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Display the specified Promotion.
     * GET|HEAD /promotions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $promotion = $this->promotionService->findPromotion($id);
        $promotion->load(PromotionConstant::SIMPLE_PROMOTION_RELATIONS);

        return $this->sendResponse(new Promotion($promotion), 'Promotion retrieved successfully');
    }

    /**
     * Update the specified Promotion in storage.
     * PUT/PATCH /promotions/{id}
     *
     * @param int $id
     * @param UpdatePromotionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePromotionAPIRequest $request)
    {
        $input = $request->all();

        $promotion = $this->promotionService->updatePromotion($input, $id);
        return $this->sendResponse(new Promotion($promotion), 'Promotion updated successfully');
    }

    /**
     * Remove the specified Promotion from storage.
     * DELETE /promotions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $promotion = $this->promotionService->deletePromotion($id);
        return $this->sendSuccess('Promotion deleted successfully');
    }

    public function queryByPOSBranch(QueryByPOSBranchPromotionAPIRequest $request)
    {
        $promotions = $this->promotionService->listPOSPromotions($request);
        $promotions->load(PromotionConstant::QUERY_PROMOTION_RELATIONS);

        return $this->sendResponse(Promotion::collection($promotions), 'Promotion retrieved successfully');
    }

    public function queryByCode($code)
    {
        $promotion = $this->promotionService->findPromotionByCode($code);
        $promotion->load(PromotionConstant::SIMPLE_PROMOTION_RELATIONS);

        return $this->sendResponse(new Promotion($promotion), 'Promotion retrieved successfully');
    }
}
