<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateRegisterChopRuleAPIRequest;
use App\Http\Requests\API\UpdateRegisterChopRuleAPIRequest;
use App\Http\Requests\API\UpdateRegisterChopRulePermissionsAPIRequest;
use App\Http\Resources\RegisterChopRule;
use App\Services\RegisterChopRuleService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class RegisterChopRuleAPIController
 * @package App\Http\Controllers
 */

class RegisterChopRuleAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->registerChopRuleService = app(RegisterChopRuleService::class);
    }

    /**
     * Display a listing of the RegisterChopRule.
     * GET|HEAD /registerChopRules
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $registerChopRules = $this->registerChopRuleService->listRegisterChopRules($request);

        return $this->sendResponse(RegisterChopRule::collection($registerChopRules), 'RegisterChopRules retrieved successfully');
    }

    /**
     * Display the specified RegisterChopRule.
     * GET|HEAD /registerChopRules
     *
     * @return Response
     */
    public function get()
    {
        $registerChopRule = $this->registerChopRuleService->getRegisterChopRule();

        return $this->sendResponse(new RegisterChopRule($registerChopRule), 'RegisterChopRule retrieved successfully');
    }

    /**
     * Update the specified RegisterChopRule in storage.
     * PUT/PATCH /registerChopRules/{id}
     *
     * @param int $id
     * @param UpdateRegisterChopRuleAPIRequest $request
     *
     * @return Response
     */
    public function update(UpdateRegisterChopRuleAPIRequest $request)
    {
        $input = $request->all();

        $registerChopRule = $this->registerChopRuleService->setRegisterChopRule($input);
        return $this->sendResponse(new RegisterChopRule($registerChopRule), 'RegisterChopRule updated successfully');
    }
}
