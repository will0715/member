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
use App\Http\Resources\Member;
use App\Http\Resources\BranchWithMemberCount;
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
        
        $data = $this->reportService->getTodayDashboardData($request);
        return $this->sendResponse($data, 'retrieved successfully');
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
        
        $data = $this->reportService->getRankMemberSummary($request);
        return $this->sendResponse($data, 'retrieved successfully');
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
        
        $data = $this->reportService->getMemberGenderTransactionAmountPercentageSummary($request);
        return $this->sendResponse($data, 'retrieved successfully');
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
        
        $data = $this->reportService->getBranchChopConsumeChopSummary($request);
        return $this->sendResponse($data, 'retrieved successfully');
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
        
        $data = $this->reportService->getBranchRegisterMemberSummary($request);
        return $this->sendResponse($data, 'retrieved successfully');
    }

    public function getPrepaidcardTopupRecords(Request $request)
    {
        $data = $this->reportService->getPrepaidcardTopupRecords($request);
        $count = $this->reportService->getPrepaidcardTopupRecordsCount($request);

        return $this->sendResponseWithTotalCount(
            PrepaidcardRecord::collection($data), 
            'retrieved successfully',
            $count
        );
    }

    public function getPrepaidcardPaymentRecords(Request $request)
    {
        $data = $this->reportService->getPrepaidcardPaymentRecords($request);
        $count = $this->reportService->getPrepaidcardPaymentRecordsCount($request);

        return $this->sendResponseWithTotalCount(
            PrepaidcardRecord::collection($data), 
            'retrieved successfully',
            $count
        );
    }

    public function getAddChopsRecords(Request $request)
    {
        $data = $this->reportService->getAddChopsRecords($request);
        $count = $this->reportService->getAddChopsRecordsCount($request);

        return $this->sendResponseWithTotalCount(
            ChopRecord::collection($data), 
            'retrieved successfully',
            $count
        );
    }

    public function getConsumeChopsRecords(Request $request)
    {
        $data = $this->reportService->getConsumeChopsRecords($request);
        $count = $this->reportService->getConsumeChopsRecordsCount($request);

        return $this->sendResponseWithTotalCount(
            ChopRecord::collection($data), 
            'retrieved successfully',
            $count
        );
    }

    public function getTransactionRecords(Request $request)
    {
        $data = $this->transactionService->listTransactions($request);
        $data->load(TransactionConstant::BASIC_RELATIONS);

        $count = $this->transactionService->transactionsCount($request);

        return $this->sendResponseWithTotalCount(
            Transaction::collection($data), 
            'retrieved successfully',
            $count,
        );
    }

    public function getMemberRegisterBranchDetail(Request $request)
    {
        $data = $this->reportService->getMemberRegisterBranchDetail($request);
        $count = $this->reportService->getMemberRegisterBranchDetailCount($request);

        return $this->sendResponseWithTotalCount(
            Member::collection($data), 
            'retrieved successfully',
            $count
        );
    }

    public function getMemberRegisterBranchStatistics(Request $request)
    {
        $data = $this->reportService->getMemberRegisterBranchStatistics($request);
        return $this->sendResponse(BranchWithMemberCount::collection($data), 'retrieved successfully');
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
