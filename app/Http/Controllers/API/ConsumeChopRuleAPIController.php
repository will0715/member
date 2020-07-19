<?php

namespace App\Http\Controllers\API;

use App\Constants\ChopsRuleConstant;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateConsumeChopRuleAPIRequest;
use App\Http\Requests\API\UpdateConsumeChopRuleAPIRequest;
use App\Http\Resources\ConsumeChopRule;
use App\Services\ConsumeChopRuleService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class ConsumeChopRuleAPIController
 * @package App\Http\Controllers
 */

class ConsumeChopRuleAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->consumeChopRuleService = app(ConsumeChopRuleService::class);
    }

    /**
     * Display a listing of the ConsumeChopRule.
     * GET|HEAD /consumeChopRules
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $consumeChopRules = $this->consumeChopRuleService->listConsumeChopRules($request);
            $consumeChopRules->load(ChopsRuleConstant::BASIC_RELATIONS);
            return $this->sendResponse(ConsumeChopRule::collection($consumeChopRules), 'ConsumeChopRules retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Store a newly created ConsumeChopRule in storage.
     * POST /consumeChopRules
     *
     * @param CreateConsumeChopRuleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateConsumeChopRuleAPIRequest $request)
    {
        $input = $request->all();

        try {
            $consumeChopRule = $this->consumeChopRuleService->newConsumeChopRule($input);
            $consumeChopRule->load(ChopsRuleConstant::BASIC_RELATIONS);
            return $this->sendResponse(new ConsumeChopRule($consumeChopRule), 'ConsumeChopRule saved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Display the specified ConsumeChopRule.
     * GET|HEAD /consumeChopRules/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $consumeChopRule = $this->consumeChopRuleService->findConsumeChopRule($id);
            $consumeChopRule->load(ChopsRuleConstant::BASIC_RELATIONS);
            return $this->sendResponse(new ConsumeChopRule($consumeChopRule), 'ConsumeChopRule retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Update the specified ConsumeChopRule in storage.
     * PUT/PATCH /consumeChopRules/{id}
     *
     * @param int $id
     * @param UpdateConsumeChopRuleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConsumeChopRuleAPIRequest $request)
    {
        $input = $request->all();

        try {
            $consumeChopRule = $this->consumeChopRuleService->updateConsumeChopRule($input, $id);
            $consumeChopRule->load(ChopsRuleConstant::BASIC_RELATIONS);
            return $this->sendResponse(new ConsumeChopRule($consumeChopRule), 'ConsumeChopRule updated successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Remove the specified ConsumeChopRule from storage.
     * DELETE /consumeChopRules/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $consumeChopRule = $this->consumeChopRuleService->deleteConsumeChopRule($id);
            return $this->sendSuccess('ConsumeChopRule deleted successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
