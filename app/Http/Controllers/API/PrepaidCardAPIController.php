<?php

namespace App\Http\Controllers\API;

use App\Exceptions\PrepaidCardsNotEnoughException;
use App\Http\Requests\API\CreatePrepaidCardAPIRequest;
use App\Http\Requests\API\UpdatePrepaidCardAPIRequest;
use App\Http\Resources\Prepaidcard;
use App\Http\Resources\PrepaidcardRecord;
use App\Services\PrepaidCardService;
use App\ServiceManagers\MemberPrepaidCardServiceManager;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use Response;
use Log;
use DB;

/**
 * Class PrepaidCardController
 * @package App\Http\Controllers\API
 */

class PrepaidCardAPIController extends AppBaseController
{
    private $memberPrepaidCardServiceManager;

    public function __construct()
    {
        $this->memberPrepaidCardServiceManager = app(MemberPrepaidCardServiceManager::class);
        $this->prepaidCardService = app(PrepaidCardService::class);
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
        $records = $this->prepaidCardService->listRecords($request);
        return $this->sendResponse(PrepaidcardRecord::collection($records), 'Branches retrieved successfully');
    }
    
    public function topup(Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $topup = $this->memberPrepaidCardServiceManager->topup($input);
            DB::commit();

            return $this->sendResponse(new PrepaidcardRecord($topup), 'Top up successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function payment(Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $payment = $this->memberPrepaidCardServiceManager->payment($input);
            DB::commit();

            return $this->sendResponse(new PrepaidcardRecord($payment), 'Payment successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function voidPayment($id, Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {
            $payment = $this->memberPrepaidCardServiceManager->voidPayment($id, $input);
            DB::commit();

            return $this->sendResponse(new PrepaidcardRecord($payment), 'Void payment successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
