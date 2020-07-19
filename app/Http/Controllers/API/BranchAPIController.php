<?php

namespace App\Http\Controllers\API;

use App\Constants\BranchConstant;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateBranchAPIRequest;
use App\Http\Requests\API\UpdateBranchAPIRequest;
use App\Http\Resources\Branch;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class BranchAPIController
 * @package App\Http\Controllers
 */

class BranchAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->branchService = app(BranchService::class);
    }

    /**
     * Display a listing of the Branch.
     * GET|HEAD /branches
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $branches = $this->branchService->listBranches($request);
        return $this->sendResponse(Branch::collection($branches), 'Branches retrieved successfully');
    }

    /**
     * Store a newly created Branch in storage.
     * POST /branches
     *
     * @param CreateBranchAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBranchAPIRequest $request)
    {
        $input = $request->all();

        $branch = $this->branchService->newBranch($input);
        return $this->sendResponse(new Branch($branch), 'Branch saved successfully');
    }

    /**
     * Display the specified Branch.
     * GET|HEAD /branches/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $branch = $this->branchService->findBranch($id);
        $branch->load(BranchConstant::BRANCH_BASIC_RELATIONS);
        
        return $this->sendResponse(new Branch($branch), 'Branch retrieved successfully');
    }

    /**
     * Update the specified Branch in storage.
     * PUT/PATCH /branches/{id}
     *
     * @param int $id
     * @param UpdateBranchAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBranchAPIRequest $request)
    {
        $input = $request->all();

        $branch = $this->branchService->updateBranch($input, $id);
        return $this->sendResponse(new Branch($branch), 'Branch updated successfully');
    }

    /**
     * Remove the specified Branch from storage.
     * DELETE /branches/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $branch = $this->branchService->deleteBranch($id);
        return $this->sendSuccess('Branch deleted successfully');
    }
}
