<?php

namespace App\Http\Controllers\API;

use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Criterias\LimitOffsetCriteria;
use App\Services\ReportService;
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
        $this->reportService = new ReportService();
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
            $data = $this->reportService->getTransactionRecords($request);
            return $this->sendResponse($data, 'retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }
}
