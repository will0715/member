<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ChopsNotEnoughException;
use App\Exceptions\AlreadyVoidedException;
use App\Http\Requests\API\CreateChopAPIRequest;
use App\Http\Requests\API\UpdateChopAPIRequest;
use App\Models\Chop;
use App\Repositories\ChopRepository;
use App\Repositories\MemberRepository;
use App\Repositories\BranchRepository;
use App\Services\ChopService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;
use Log;

/**
 * Class ChopController
 * @package App\Http\Controllers\API
 */

class ChopAPIController extends AppBaseController
{
    /** @var  ChopRepository */
    private $chopRepository;
    /** @var  MemberRepository */
    private $memberRepository;
    /** @var  BranchRepository */
    private $branchRepository;

    private $chopService;

    public function __construct()
    {
        $this->chopRepository = app(ChopRepository::class);
        $this->memberRepository = app(MemberRepository::class);
        $this->branchRepository = app(BranchRepository::class);
        $this->chopService = new ChopService();
        
    }

    /**
     * Display a listing of the Chop.
     * GET|HEAD /chops
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->chopRepository->pushCriteria(new RequestCriteria($request));
        $this->chopRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chops = $this->chopRepository->all();

        return $this->sendResponse($chops->toArray(), 'Chops retrieved successfully');
    }

    /**
     * Store a newly created Chop in storage.
     * POST /chops
     *
     * @param CreateChopAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateChopAPIRequest $request)
    {
        $input = $request->all();

        $chop = $this->chopRepository->create($input);

        return $this->sendResponse($chop->toArray(), 'Chop saved successfully');
    }

    /**
     * Display the specified Chop.
     * GET|HEAD /chops/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Chop $chop */
        $chop = $this->chopRepository->find($id);

        if (empty($chop)) {
            return $this->sendError('Chop not found');
        }

        return $this->sendResponse($chop->toArray(), 'Chop retrieved successfully');
    }

    /**
     * Update the specified Chop in storage.
     * PUT/PATCH /chops/{id}
     *
     * @param int $id
     * @param UpdateChopAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChopAPIRequest $request)
    {
        $input = $request->all();

        /** @var Chop $chop */
        $chop = $this->chopRepository->find($id);

        if (empty($chop)) {
            return $this->sendError('Chop not found');
        }

        $chop = $this->chopRepository->update($input, $id);

        return $this->sendResponse($chop->toArray(), 'Chop updated successfully');
    }

    /**
     * Remove the specified Chop from storage.
     * DELETE /chops/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Chop $chop */
        $chop = $this->chopRepository->find($id);

        if (empty($chop)) {
            return $this->sendError('Chop not found');
        }

        $chop->delete();

        return $this->sendSuccess('Chop deleted successfully');
    }

    public function queryChops(Request $request)
    {

    }
    
    public function manualAddChops(Request $request)
    {
        $memberId = $request->get('member_id');
        $branchId = $request->get('branch_id');
        $chops = $request->get('chops');
        $customer = $this->getCustomer($request);

        $member = $this->memberRepository->findByPhone($memberId);
        if (!$member) {
            return $this->sendError('member not exist', 404);
        }
    
        $branch = $this->branchRepository->findByBranchId($branchId);
        if (!$branch) {
            return $this->sendError('branch not exist', 404);
        }

        try {
            $this->chopService->setCustomer($customer);
            $this->chopService->setMember($member);
            $this->chopService->setBranch($branch);

            $this->chopService->manualAddChops($chops);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Add Chops failed', 500);
        }
        
        return $this->sendSuccess('Add Chops successfully');
    }
    
    public function consumeChops(Request $request)
    {
        $memberId = $request->get('member_id');
        $branchId = $request->get('branch_id');
        $chops = $request->get('chops');
        $customer = $this->getCustomer($request);

        $member = $this->memberRepository->findByPhone($memberId);
        if (!$member) {
            return $this->sendError('member not exist', 404);
        }
    
        $branch = $this->branchRepository->findByBranchId($branchId);
        if (!$branch) {
            return $this->sendError('branch not exist', 404);
        }

        try {
            // TODO: 兌點
            $this->chopService->setCustomer($customer);
            $this->chopService->setMember($member);
            $this->chopService->setBranch($branch);

            $record = $this->chopService->consumeChops($chops);
            return $this->sendResponse($record->toArray(), 'Consume Chops successfully');
        } catch (ChopsNotEnoughException $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), 409);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Consume Chops failed', 500);
        }
        
    }
    
    public function voidConsumeChops($id, Request $request)
    {
        $customer = $this->getCustomer($request);
        try {
            $this->chopService->setCustomer($customer);

            $this->chopService->voidConsumeChops($id);
        } catch (AlreadyVoidedException $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), 409);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Consume Chops failed', 500);
        }
        
        return $this->sendSuccess('Void successfully');
    }
}
