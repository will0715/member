<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateConsumeChopRuleAPIRequest;
use App\Http\Requests\API\UpdateConsumeChopRuleAPIRequest;
use App\Models\ConsumeChopRule;
use App\Repositories\ConsumeChopRuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;

/**
 * Class ConsumeChopRuleController
 * @package App\Http\Controllers\API
 */

class ConsumeChopRuleAPIController extends AppBaseController
{
    /** @var  ConsumeChopRuleRepository */
    private $consumeChopRuleRepository;

    public function __construct(ConsumeChopRuleRepository $consumeChopRuleRepo)
    {
        $this->consumeChopRuleRepository = $consumeChopRuleRepo;
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
        $this->consumeChopRuleRepository->pushCriteria(new RequestCriteria($request));
        $this->consumeChopRuleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $consumeChopRules = $this->consumeChopRuleRepository->with('rank')->all();

        return $this->sendResponse($consumeChopRules->toArray(), 'Consume Chop Rules retrieved successfully');
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

        $consumeChopRule = $this->consumeChopRuleRepository->create($input);

        return $this->sendResponse($consumeChopRule->toArray(), 'Consume Chop Rule saved successfully');
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
        /** @var ConsumeChopRule $consumeChopRule */
        $consumeChopRule = $this->consumeChopRuleRepository->find($id);

        if (empty($consumeChopRule)) {
            return $this->sendError('Consume Chop Rule not found');
        }

        return $this->sendResponse($consumeChopRule->toArray(), 'Consume Chop Rule retrieved successfully');
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

        /** @var ConsumeChopRule $consumeChopRule */
        $consumeChopRule = $this->consumeChopRuleRepository->find($id);

        if (empty($consumeChopRule)) {
            return $this->sendError('Consume Chop Rule not found');
        }

        $consumeChopRule = $this->consumeChopRuleRepository->update($input, $id);

        return $this->sendResponse($consumeChopRule->toArray(), 'ConsumeChopRule updated successfully');
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
        /** @var ConsumeChopRule $consumeChopRule */
        $consumeChopRule = $this->consumeChopRuleRepository->find($id);

        if (empty($consumeChopRule)) {
            return $this->sendError('Consume Chop Rule not found');
        }

        $consumeChopRule->delete();

        return $this->sendSuccess('Consume Chop Rule deleted successfully');
    }
}
