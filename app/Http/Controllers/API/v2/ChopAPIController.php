<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Requests\API\CreateChopAPIRequest;
use App\Http\Requests\API\UpdateChopAPIRequest;
use App\Http\Requests\API\ManualAddChopAPIRequest;
use App\Http\Requests\API\ConsumeChopAPIRequest;
use App\Http\Requests\API\EarnChopAPIRequest;
use App\Http\Resources\Chop;
use App\Http\Resources\ChopRecord;
use App\Http\Resources\ConsumeChopSummaryRecord;
use App\Services\ChopService;
use App\ServiceManagers\v2\MemberChopServiceManager;
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
