<?php

namespace App\Http\Controllers\API;

use App\Constants\MemberConstant;
use App\Http\Controllers\AppBaseController;
use App\ServiceManagers\MemberChopServiceManager;
use App\ServiceManagers\MemberPrepaidCardServiceManager;
use App\ServiceManagers\MemberRegisterManager;
use App\Http\Requests\API\CreateMemberAPIRequest;
use App\Http\Requests\API\UpdateMemberAPIRequest;
use App\Http\Helpers\MemberResourceHelper;
use App\Http\Resources\Member;
use App\Http\Resources\MemberByQuery;
use App\Services\MemberService;
use App\Services\ChopService;
use App\Services\RankService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Hash;
use Log;

/**
 * Class MemberAPIController
 * @package App\Http\Controllers
 */

class MemberAPIController extends AppBaseController
{
    /** @var  MemberRepository */
    private $memberRepository;

    public function __construct()
    {
        $this->memberService = app(MemberService::class);
        $this->rankService = app(RankService::class);
        $this->chopService = app(ChopService::class);
        $this->memberChopServiceManager = app(MemberChopServiceManager::class);
        $this->memberPrepaidCardServiceManager = app(MemberPrepaidCardServiceManager::class);
        $this->memberRegisterManager = app(MemberRegisterManager::class);
    }

    /**
     * Display a listing of the Member.
     * GET|HEAD /members
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $members = $this->memberService->listMembers($request);
            $members->load(MemberConstant::BASE_MEMBER_RELATIONS);

            return $this->sendResponse(Member::collection($members), 'Members retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Store a newly created Member in storage.
     * POST /members
     *
     * @param CreateMemberAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberAPIRequest $request)
    {
        $input = $request->all();

        try {
            $member = $this->memberRegisterManager->registerMember($input);
            return $this->sendResponse(new Member($member), 'Member saved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $member = $this->memberService->findMember($id);
            $member->load(MemberConstant::BASE_MEMBER_RELATIONS);

            return $this->sendResponse(new Member($member), 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Update the specified Member in storage.
     * PUT/PATCH /members/{id}
     *
     * @param int $id
     * @param UpdateMemberAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberAPIRequest $request)
    {
        $input = $request->all();

        try {
            $member = $this->memberService->updateMember($input, $id);
            return $this->sendResponse(new Member($member), 'Member updated successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Remove the specified Member from storage.
     * DELETE /members/{id}
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
            $member = $this->memberService->deleteMember($id);
            return $this->sendSuccess('Member deleted successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    public function queryByPhone(Request $request)
    {
        $phone = $request->get('phone');
        $branchId = $request->get('branch_id');

        try {
            $member = $this->memberChopServiceManager->getMemberWithChops([
                'phone' => $phone,
                'branch_id' => $branchId
            ]);
            $member->load(MemberConstant::BASE_MEMBER_RELATIONS);

            return $this->sendResponse(new MemberByQuery($member), 'Member query successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChops($phone)
    {
        try {
            $chops = $this->memberChopServiceManager->getMemberTotalChops([
                'phone' => $phone
            ]);
            return $this->sendResponse(['chops' => $chops], 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChopsDetail($phone)
    {
        try {
            $chops = $this->memberChopServiceManager->getMemberChopsDetail([
                'phone' => $phone
            ]);
            return $this->sendResponse($chops, 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getOrderRecords($phone)
    {
        try {
            $orderRecords = $this->memberChopServiceManager->getMemberChopsDetail([
                'phone' => $phone
            ]);
            return $this->sendResponse($orderRecords, 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    public function getBalance($phone)
    {
        try {
            $balance = $this->memberPrepaidCardServiceManager->getMemberBalance([
                'phone' => $phone
            ]);
            return $this->sendResponse($balance, 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
    public function information($phone)
    {
        try {
            $member = $this->memberService->findMemberByPhone($phone);
            $member->load(MemberConstant::ALL_MEMBER_RELATIONS);

            return $this->sendResponse(new Member($member), 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    public function detail($id)
    {
        try {
            $member = $this->memberService->findMember($id);
            $member->load(MemberConstant::ALL_MEMBER_RELATIONS);

            return $this->sendResponse(new Member($member), 'Member retrieved successfully');
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
