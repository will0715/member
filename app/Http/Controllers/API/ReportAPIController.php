<?php

namespace App\Http\Controllers\API;

use App\Constants\TransactionConstant;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\ChopRecord;
use App\Http\Resources\PrepaidcardRecord;
use App\Http\Resources\Transaction;
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
        
        $data = $this->reportService->dashboard($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function todayDashboard(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');
        
        try {
            $data = $this->reportService->getTodayDashboardData($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function rankMemberSummary(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');
        
        try {
            $data = $this->reportService->getRankMemberSummary($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function memberGenderTransactionAmountPercentageSummary(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');
        
        try {
            $data = $this->reportService->getMemberGenderTransactionAmountPercentageSummary($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function branchChopConsumeChopSummary(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');
        
        try {
            $data = $this->reportService->getBranchChopConsumeChopSummary($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function branchRegisterMemberSummary(Request $request)
    {
        $startAt = $request->get('start');
        $endAt = $request->get('end');
        
        try {
            $data = $this->reportService->getBranchRegisterMemberSummary($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }

    public function getPrepaidcardTopupRecords(Request $request)
    {
        $data = $this->reportService->getPrepaidcardTopupRecords($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getPrepaidcardPaymentRecords(Request $request)
    {
        $data = $this->reportService->getPrepaidcardPaymentRecords($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getAddChopsRecords(Request $request)
    {
        $data = $this->reportService->getAddChopsRecords($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getConsumeChopsRecords(Request $request)
    {
        $data = $this->reportService->getConsumeChopsRecords($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getTransactionRecords(Request $request)
    {
        $data = $this->transactionService->listTransactions($request);
        $data->load(TransactionConstant::BASIC_RELATIONS);

        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getMemberRegisterBranchDetail(Request $request)
    {
        $data = $this->reportService->getMemberRegisterBranchDetail($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getMemberRegisterBranchStatistics(Request $request)
    {
        $data = $this->reportService->getMemberRegisterBranchStatistics($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getMemberCountByDate(Request $request)
    {
        $data = $this->reportService->getMemberCountByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getBranchCountByDate(Request $request)
    {
        $data = $this->reportService->getBranchCountByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getEarnChopsByDate(Request $request)
    {
        $data = $this->reportService->getEarnChopsByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getConsumeChopsByDate(Request $request)
    {
        $data = $this->reportService->getConsumeChopsByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getTransactionCountByDate(Request $request)
    {
        $data = $this->reportService->getTransactionCountByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getTransactionAmountByDate(Request $request)
    {
        $data = $this->reportService->getTransactionAmountByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getPrepaidCardTopupByDate(Request $request)
    {
        $data = $this->reportService->getPrepaidCardTopupByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getPrepaidCardPaymentByDate(Request $request)
    {
        $data = $this->reportService->getPrepaidCardPaymentByDate($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }
}
