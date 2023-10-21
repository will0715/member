<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateChopAPIRequest;
use App\Http\Requests\API\UpdateChopAPIRequest;
use App\Http\Requests\API\ManualAddChopAPIRequest;
use App\Http\Requests\API\ConsumeChopAPIRequest;
use App\Http\Requests\API\EarnChopAPIRequest;
use App\Http\Resources\Chop;
use App\Http\Resources\ChopRecord;
use App\Http\Resources\ConsumeChopSummaryRecord;
use App\Services\ChopService;
use App\ServiceManagers\MemberChopServiceManager;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Log;
use DB;

/**
 * Class ChopController
 * @package App\Http\Controllers\API
 */

class ChopAPIController extends AppBaseController
{
    private $chopService;

    public function __construct()
    {
        $this->memberChopServiceManager = app(MemberChopServiceManager::class);
        $this->chopService = app(ChopService::class);
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
        $records = $this->chopService->listRecords($request);
        return $this->sendResponse(ChopRecord::collection($records), 'Chops retrieved successfully');
    }

    public function manualAddChops(ManualAddChopAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $chops = $this->memberChopServiceManager->manualAddChops($input);
            DB::commit();

            return $this->sendResponse(new ChopRecord($chops), 'Add Chops successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function earnChops(EarnChopAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $chops = $this->memberChopServiceManager->earnChops($input);
            DB::commit();

            return $this->sendResponse(new ChopRecord($chops), 'Add Chops successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function voidEarnChops($id, Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $chops = $this->memberChopServiceManager->voidEarnChops($id, $input);
            DB::commit();

            return $this->sendResponse(new ChopRecord($chops), 'Void successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function consumeChops(ConsumeChopAPIRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $record = $this->memberChopServiceManager->consumeChops($input);
            DB::commit();

            return $this->sendResponse(new ConsumeChopSummaryRecord($record), 'Consume Chops successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function voidConsumeChops($id, Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $record = $this->memberChopServiceManager->voidConsumeChops($id, $input);
            DB::commit();

            return $this->sendResponse(new ConsumeChopSummaryRecord($record), 'Void successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
