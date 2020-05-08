<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEarnChopRuleAPIRequest;
use App\Http\Requests\API\UpdateEarnChopRuleAPIRequest;
use App\Models\EarnChopRule;
use App\Repositories\EarnChopRuleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;

/**
 * Class EarnChopRuleController
 * @package App\Http\Controllers\API
 */

class EarnChopRuleAPIController extends AppBaseController
{
    /** @var  EarnChopRuleRepository */
    private $earnChopRuleRepository;

    public function __construct(EarnChopRuleRepository $earnChopRuleRepo)
    {
        $this->earnChopRuleRepository = $earnChopRuleRepo;
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
        $this->earnChopRuleRepository->pushCriteria(new RequestCriteria($request));
        $this->earnChopRuleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $earnChopRules = $this->earnChopRuleRepository->with('rank')->all();

        return $this->sendResponse($earnChopRules->toArray(), 'Earn Chop Rules retrieved successfully');
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

        $earnChopRule = $this->earnChopRuleRepository->create($input);

        return $this->sendResponse($earnChopRule->toArray(), 'Earn Chop Rule saved successfully');
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
        /** @var EarnChopRule $earnChopRule */
        $earnChopRule = $this->earnChopRuleRepository->find($id);

        if (empty($earnChopRule)) {
            return $this->sendError('Earn Chop Rule not found');
        }

        return $this->sendResponse($earnChopRule->toArray(), 'Earn Chop Rule retrieved successfully');
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

        /** @var EarnChopRule $earnChopRule */
        $earnChopRule = $this->earnChopRuleRepository->find($id);

        if (empty($earnChopRule)) {
            return $this->sendError('Earn Chop Rule not found');
        }

        $earnChopRule = $this->earnChopRuleRepository->update($input, $id);

        return $this->sendResponse($earnChopRule->toArray(), 'EarnChopRule updated successfully');
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
        /** @var EarnChopRule $earnChopRule */
        $earnChopRule = $this->earnChopRuleRepository->find($id);

        if (empty($earnChopRule)) {
            return $this->sendError('Earn Chop Rule not found');
        }

        $earnChopRule->delete();

        return $this->sendSuccess('Earn Chop Rule deleted successfully');
    }
}
