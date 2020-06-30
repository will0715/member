<?php

namespace App\Http\Controllers\API;

use App\Constants\ChopsRuleConstant;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateEarnChopRuleAPIRequest;
use App\Http\Requests\API\UpdateEarnChopRuleAPIRequest;
use App\Http\Resources\EarnChopRule;
use App\Services\EarnChopRuleService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class EarnChopRuleAPIController
 * @package App\Http\Controllers
 */

class EarnChopRuleAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->earnChopRuleService = app(EarnChopRuleService::class);
    }

    /**
     * Display a listing of the EarnChopRule.
     * GET|HEAD /earnChopRules
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $earnChopRules = $this->earnChopRuleService->listEarnChopRules($request);
        $earnChopRules->load(ChopsRuleConstant::BASIC_RELATIONS);
        return $this->sendResponse(EarnChopRule::collection($earnChopRules), 'EarnChopRules retrieved successfully');
    }

    /**
     * Store a newly created EarnChopRule in storage.
     * POST /earnChopRules
     *
     * @param CreateEarnChopRuleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEarnChopRuleAPIRequest $request)
    {
        $input = $request->all();

        $earnChopRule = $this->earnChopRuleService->newEarnChopRule($input);
        $earnChopRule->load(ChopsRuleConstant::BASIC_RELATIONS);
        return $this->sendResponse(new EarnChopRule($earnChopRule), 'EarnChopRule saved successfully');
    }

    /**
     * Display the specified EarnChopRule.
     * GET|HEAD /earnChopRules/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $earnChopRule = $this->earnChopRuleService->findEarnChopRule($id);
        $earnChopRule->load(ChopsRuleConstant::BASIC_RELATIONS);
        return $this->sendResponse(new EarnChopRule($earnChopRule), 'EarnChopRule retrieved successfully');
    }

    /**
     * Update the specified EarnChopRule in storage.
     * PUT/PATCH /earnChopRules/{id}
     *
     * @param int $id
     * @param UpdateEarnChopRuleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEarnChopRuleAPIRequest $request)
    {
        $input = $request->all();

        $earnChopRule = $this->earnChopRuleService->updateEarnChopRule($input, $id);
        $earnChopRule->load(ChopsRuleConstant::BASIC_RELATIONS);
        return $this->sendResponse(new EarnChopRule($earnChopRule), 'EarnChopRule updated successfully');
    }

    /**
     * Remove the specified EarnChopRule from storage.
     * DELETE /earnChopRules/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $earnChopRule = $this->earnChopRuleService->deleteEarnChopRule($id);
        return $this->sendSuccess('EarnChopRule deleted successfully');
    }
}
