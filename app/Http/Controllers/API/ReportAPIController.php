<?php

namespace App\Http\Controllers\API;

use App\Constants\TransactionConstant;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Services\ReportService;
use App\Services\TransactionService;
use Response;
use Auth;
use Log;

/**
 * Class UserAPIController
 * @package App\Http\Controllers\API
 */

class ReportAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
        $this->reportService = app(ReportService::class);
        $this->transactionService = app(TransactionService::class);
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function dashboard(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');
        
        try {
            $data = $this->reportService->dashboard($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getPrepaidcardTopupRecords(Request $request)
    {
        try {
            $data = $this->reportService->getPrepaidcardTopupRecords($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getPrepaidcardPaymentRecords(Request $request)
    {
        try {
            $data = $this->reportService->getPrepaidcardPaymentRecords($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getAddChopsRecords(Request $request)
    {
        try {
            $data = $this->reportService->getAddChopsRecords($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getConsumeChopsRecords(Request $request)
    {
        try {
            $data = $this->reportService->getConsumeChopsRecords($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getTransactionRecords(Request $request)
    {
        try {
            $data = $this->transactionService->listTransactions($request);
            $data->load(TransactionConstant::BASIC_RELATIONS);

            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getMemberRegisterBranchDetail(Request $request)
    {
        try {
            $data = $this->reportService->getMemberRegisterBranchDetail($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getMemberRegisterBranchStatistics(Request $request)
    {
        try {
            $data = $this->reportService->getMemberRegisterBranchStatistics($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }
}
