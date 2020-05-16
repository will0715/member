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
        $chops = $request->get('chops');
        
        try {
            $data = $this->reportService->dashboard($startAt, $endAt);
            return $this->sendResponse($data, 'Users retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            return $this->sendError('Get Dashboard Data Failed', 500);
        }
    }
}
