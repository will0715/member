<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChopExpiredSettingAPIRequest;
use App\Http\Requests\API\UpdateChopExpiredSettingAPIRequest;
use App\Models\ChopExpiredSetting;
use App\Repositories\ChopExpiredSettingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;

/**
 * Class ChopExpiredSettingController
 * @package App\Http\Controllers\API
 */

class ChopExpiredSettingAPIController extends AppBaseController
{
    /** @var  ChopExpiredSettingRepository */
    private $chopExpiredSettingRepository;

    public function __construct(ChopExpiredSettingRepository $chopExpiredSettingRepo)
    {
        $this->chopExpiredSettingRepository = $chopExpiredSettingRepo;
    }

    /**
     * Display a listing of the ChopExpiredSetting.
     * GET|HEAD /chopExpiredSettings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->chopExpiredSettingRepository->pushCriteria(new RequestCriteria($request));
        $this->chopExpiredSettingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $chopExpiredSettings = $this->chopExpiredSettingRepository->all();

        return $this->sendResponse($chopExpiredSettings->toArray(), 'Chop Expired Settings retrieved successfully');
    }

    /**
     * Store a newly created ChopExpiredSetting in storage.
     * POST /chopExpiredSettings
     *
     * @param CreateChopExpiredSettingAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateChopExpiredSettingAPIRequest $request)
    {
        $input = $request->all();

        $chopExpiredSetting = $this->chopExpiredSettingRepository->create($input);

        return $this->sendResponse($chopExpiredSetting->toArray(), 'Chop Expired Setting saved successfully');
    }

    /**
     * Display the specified ChopExpiredSetting.
     * GET|HEAD /chopExpiredSettings/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ChopExpiredSetting $chopExpiredSetting */
        $chopExpiredSetting = $this->chopExpiredSettingRepository->find($id);

        if (empty($chopExpiredSetting)) {
            return $this->sendError('Chop Expired Setting not found');
        }

        return $this->sendResponse($chopExpiredSetting->toArray(), 'Chop Expired Setting retrieved successfully');
    }

    /**
     * Update the specified ChopExpiredSetting in storage.
     * PUT/PATCH /chopExpiredSettings/{id}
     *
     * @param int $id
     * @param UpdateChopExpiredSettingAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChopExpiredSettingAPIRequest $request)
    {
        $input = $request->all();

        /** @var ChopExpiredSetting $chopExpiredSetting */
        $chopExpiredSetting = $this->chopExpiredSettingRepository->find($id);

        if (empty($chopExpiredSetting)) {
            return $this->sendError('Chop Expired Setting not found');
        }

        $chopExpiredSetting = $this->chopExpiredSettingRepository->update($input, $id);

        return $this->sendResponse($chopExpiredSetting->toArray(), 'ChopExpiredSetting updated successfully');
    }

    /**
     * Remove the specified ChopExpiredSetting from storage.
     * DELETE /chopExpiredSettings/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ChopExpiredSetting $chopExpiredSetting */
        $chopExpiredSetting = $this->chopExpiredSettingRepository->find($id);

        if (empty($chopExpiredSetting)) {
            return $this->sendError('Chop Expired Setting not found');
        }

        $chopExpiredSetting->delete();

        return $this->sendSuccess('Chop Expired Setting deleted successfully');
    }
}
