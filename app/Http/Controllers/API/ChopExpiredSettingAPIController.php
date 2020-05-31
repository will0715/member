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
        try {
            $chopExpiredSettings = $this->chopExpiredSettingService->listChopExpiredSettings($request);
            return $this->sendResponse(ChopExpiredSetting::collection($chopExpiredSettings), 'ChopExpiredSettings retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
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

        try {
            $chopExpiredSetting = $this->chopExpiredSettingService->newChopExpiredSetting($input);
            return $this->sendResponse(new ChopExpiredSetting($chopExpiredSetting), 'ChopExpiredSetting saved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
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
        try {
            $chopExpiredSetting = $this->chopExpiredSettingService->findChopExpiredSetting($id);
            return $this->sendResponse(new ChopExpiredSetting($chopExpiredSetting), 'ChopExpiredSetting retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
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

        try {
            $chopExpiredSetting = $this->chopExpiredSettingService->updateChopExpiredSetting($input, $id);
            return $this->sendResponse(new ChopExpiredSetting($chopExpiredSetting), 'ChopExpiredSetting updated successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
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
        try {
            $chopExpiredSetting = $this->chopExpiredSettingService->deleteChopExpiredSetting($id);
            return $this->sendSuccess('ChopExpiredSetting deleted successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
