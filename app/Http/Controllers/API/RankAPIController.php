<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateRankAPIRequest;
use App\Http\Requests\API\UpdateRankAPIRequest;
use App\Http\Resources\Rank;
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
}
