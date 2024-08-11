<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateRankAPIRequest;
use App\Http\Requests\API\UpdateRankAPIRequest;
use App\Http\Requests\API\UpdateRankDiscountAPIRequest;
use App\Http\Requests\API\UpdateRankExpiredSettingAPIRequest;
use App\Http\Requests\API\UpdateRankUpgradeSettingAPIRequest;
use App\Http\Resources\Rank;
use App\Http\Resources\RankDiscount;
use App\Http\Resources\RankExpiredSetting;
use App\Http\Resources\RankUpgradeSetting;
use App\Services\RankService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class RankAPIController
 * @package App\Http\Controllers
 */

class RankAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->rankService = app(RankService::class);
    }

    /**
     * Display a listing of the Rank.
     * GET|HEAD /ranks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $ranks = $this->rankService->listRanks($request);
        $ranks->loadCount('members');
        return $this->sendResponse(Rank::collection($ranks), 'Ranks retrieved successfully');
    }

    /**
     * Store a newly created Rank in storage.
     * POST /ranks
     *
     * @param CreateRankAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRankAPIRequest $request)
    {
        $input = $request->all();

        $rank = $this->rankService->newRank($input);
        return $this->sendResponse(new Rank($rank), 'Rank saved successfully');
    }

    /**
     * Display the specified Rank.
     * GET|HEAD /ranks/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rank = $this->rankService->findRank($id);
        return $this->sendResponse(new Rank($rank), 'Rank retrieved successfully');
    }

    /**
     * Update the specified Rank in storage.
     * PUT/PATCH /ranks/{id}
     *
     * @param int $id
     * @param UpdateRankAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRankAPIRequest $request)
    {
        $input = $request->all();

        $rank = $this->rankService->updateRank($input, $id);
        return $this->sendResponse(new Rank($rank), 'Rank updated successfully');
    }

    /**
     * Remove the specified Rank from storage.
     * DELETE /ranks/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rank = $this->rankService->deleteRank($id);
        return $this->sendSuccess('Rank deleted successfully');
    }

    public function listRankDiscount(Request $request)
    {
        $rankDiscounts = $this->rankService->listRankDiscounts($request);
        $rankDiscounts->load('rank');
        return $this->sendResponse(RankDiscount::collection($rankDiscounts), 'Rank Discounts retrieved successfully');
    }

    public function getRankDiscount($rankId)
    {
        $rankDiscount = $this->rankService->getRankDiscount($rankId);
        return $this->sendResponse(new RankDiscount($rankDiscount), 'Rank Discount retrieved successfully');
    }

    public function setRankDiscount($rankId, UpdateRankDiscountAPIRequest $request)
    {
        $input = $request->all();

        $rankDiscount = $this->rankService->setRankDiscount($input, $rankId);
        return $this->sendResponse(new RankDiscount($rankDiscount), 'Rank Discount retrieved successfully');
    }

    public function getExpiredSetting()
    {
        $setting = $this->rankService->getRankExpiredSetting();
        return $this->sendResponse(new RankExpiredSetting($setting), 'Rank Expired Setting retrieved successfully');
    }

    public function setExpiredSetting(UpdateRankExpiredSettingAPIRequest $request)
    {
        $input = $request->all();

        $setting = $this->rankService->setRankExpiredSetting($input);
        return $this->sendResponse(new RankExpiredSetting($setting), 'Rank Expired Setting updated successfully');
    }

    public function getRankUpgradeSetting($rankId)
    {
        $setting = $this->rankService->getRankUpgradeSetting($rankId);
        return $this->sendResponse(new RankUpgradeSetting($setting), 'Rank Upgrade Setting retrieved successfully');
    }

    public function setRankUpgradeSetting($rankId, UpdateRankUpgradeSettingAPIRequest $request)
    {
        $input = $request->all();

        $setting = $this->rankService->setRankUpgradeSetting($input, $rankId);
        return $this->sendResponse(new RankUpgradeSetting($setting), 'Rank Upgrade Setting updated successfully');
    }
}
