<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateChopExpiredSettingAPIRequest;
use App\Http\Requests\API\UpdateChopExpiredSettingAPIRequest;
use App\Http\Resources\ChopExpiredSetting;
use App\Services\ChopExpiredSettingService;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class ChopExpiredSettingAPIController
 * @package App\Http\Controllers
 */

class ChopExpiredSettingAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->chopExpiredSettingService = app(ChopExpiredSettingService::class);
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
        $chopExpiredSettings = $this->chopExpiredSettingService->listChopExpiredSettings($request);
        return $this->sendResponse(ChopExpiredSetting::collection($chopExpiredSettings), 'ChopExpiredSettings retrieved successfully');
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

        $chopExpiredSetting = $this->chopExpiredSettingService->newChopExpiredSetting($input);
        return $this->sendResponse(new ChopExpiredSetting($chopExpiredSetting), 'ChopExpiredSetting saved successfully');
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
        $chopExpiredSetting = $this->chopExpiredSettingService->findChopExpiredSetting($id);
        return $this->sendResponse(new ChopExpiredSetting($chopExpiredSetting), 'ChopExpiredSetting retrieved successfully');
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

        $chopExpiredSetting = $this->chopExpiredSettingService->updateChopExpiredSetting($input, $id);
        return $this->sendResponse(new ChopExpiredSetting($chopExpiredSetting), 'ChopExpiredSetting updated successfully');
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
        $chopExpiredSetting = $this->chopExpiredSettingService->deleteChopExpiredSetting($id);
        return $this->sendSuccess('ChopExpiredSetting deleted successfully');
    }
}
