<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChopRecordAPIRequest;
use App\Http\Requests\API\UpdateChopRecordAPIRequest;
use App\Models\ChopRecord;
use App\Repositories\ChopRecordRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class ChopRecordController
 * @package App\Http\Controllers\API
 */

class ChopRecordAPIController extends AppBaseController
{
    /** @var  ChopRecordRepository */
    private $chopRecordRepository;

    public function __construct(ChopRecordRepository $chopRecordRepo)
    {
        $this->chopRecordRepository = $chopRecordRepo;
    }

    /**
     * Display a listing of the ChopRecord.
     * GET|HEAD /chopRecords
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $chopRecords = $this->chopRecordRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($chopRecords->toArray(), 'Chop Records retrieved successfully');
    }

    /**
     * Store a newly created ChopRecord in storage.
     * POST /chopRecords
     *
     * @param CreateChopRecordAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateChopRecordAPIRequest $request)
    {
        $input = $request->all();

        $chopRecord = $this->chopRecordRepository->create($input);

        return $this->sendResponse($chopRecord->toArray(), 'Chop Record saved successfully');
    }

    /**
     * Display the specified ChopRecord.
     * GET|HEAD /chopRecords/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ChopRecord $chopRecord */
        $chopRecord = $this->chopRecordRepository->find($id);

        if (empty($chopRecord)) {
            return $this->sendError('Chop Record not found');
        }

        return $this->sendResponse($chopRecord->toArray(), 'Chop Record retrieved successfully');
    }

    /**
     * Update the specified ChopRecord in storage.
     * PUT/PATCH /chopRecords/{id}
     *
     * @param int $id
     * @param UpdateChopRecordAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChopRecordAPIRequest $request)
    {
        $input = $request->all();

        /** @var ChopRecord $chopRecord */
        $chopRecord = $this->chopRecordRepository->find($id);

        if (empty($chopRecord)) {
            return $this->sendError('Chop Record not found');
        }

        $chopRecord = $this->chopRecordRepository->update($input, $id);

        return $this->sendResponse($chopRecord->toArray(), 'ChopRecord updated successfully');
    }

    /**
     * Remove the specified ChopRecord from storage.
     * DELETE /chopRecords/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ChopRecord $chopRecord */
        $chopRecord = $this->chopRecordRepository->find($id);

        if (empty($chopRecord)) {
            return $this->sendError('Chop Record not found');
        }

        $chopRecord->delete();

        return $this->sendSuccess('Chop Record deleted successfully');
    }
}
