<?php

namespace App\Http\Controllers\API;

use App\Constants\TransactionConstant;
use App\Constants\MemberConstant;
use App\Http\Controllers\AppBaseController;
use App\ServiceManagers\MemberChopServiceManager;
use App\ServiceManagers\MemberPrepaidCardServiceManager;
use App\ServiceManagers\MemberRegisterManager;
use App\Http\Requests\API\CreateMemberAPIRequest;
use App\Http\Requests\API\UpdateMemberAPIRequest;
use App\Http\Requests\API\UpdateMemberByPhoneAPIRequest;
use App\Http\Helpers\MemberResourceHelper;
use App\Http\Resources\Member;
use App\Http\Resources\MemberByQuery;
use App\Http\Resources\ChopRecord;
use App\Http\Resources\PrepaidcardRecord;
use App\Services\MemberService;
use App\Services\ChopService;
use App\Services\PrepaidCardService;
use App\Services\RankService;
use App\Services\TransactionService;
use App\ServiceManagers\TransactionManager;
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
        $this->prepaidCardService = app(PrepaidCardService::class);
        $this->memberChopServiceManager = app(MemberChopServiceManager::class);
        $this->memberPrepaidCardServiceManager = app(MemberPrepaidCardServiceManager::class);
        $this->memberRegisterManager = app(MemberRegisterManager::class);
        $this->transactionManager = app(TransactionManager::class);
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
        $members = $this->memberService->listMembers($request);
        $members->load(MemberConstant::BASE_MEMBER_RELATIONS);

        return $this->sendResponse(Member::collection($members), 'Members retrieved successfully');
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

        $member = $this->memberRegisterManager->registerMember($input);
        return $this->sendResponse(new Member($member), 'Member saved successfully');
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
        $member = $this->memberService->findMember($id);
        $member->load(MemberConstant::BASE_MEMBER_RELATIONS);

        return $this->sendResponse(new Member($member), 'Member retrieved successfully');
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

        $member = $this->memberService->updateMember($input, $id);
        return $this->sendResponse(new Member($member), 'Member updated successfully');
    }

    /**
     * Update the specified Member in storage.
     * PUT/PATCH /members/{phone}
     *
     * @param int $phone
     * @param UpdateMemberByPhoneAPIRequest $request
     *
     * @return Response
     */
    public function updateByPhone($phone, UpdateMemberByPhoneAPIRequest $request)
    {
        $input = $request->all();

        $member = $this->memberService->updateMemberByPhone($input, $phone);
        return $this->sendResponse(new Member($member), 'Member updated successfully');
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
        $member = $this->memberService->deleteMember($id);
        return $this->sendSuccess('Member deleted successfully');
    }

    /**
     * Remove the specified Member from storage.
     * DELETE /members/{id}/force
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function forceDelete($id)
    {
        $member = $this->memberService->forceDeleteMember($id);
        return $this->sendSuccess('Member deleted successfully');
    }

    public function queryByPhone(Request $request)
    {
        $phone = $request->get('phone');
        $branchId = $request->get('branch_id');

        $member = $this->memberChopServiceManager->getMemberWithChops([
            'phone' => $phone,
            'branch_id' => $branchId
        ]);
        $member->load(MemberConstant::BASE_MEMBER_RELATIONS);

        return $this->sendResponse(new MemberByQuery($member), 'Member query successfully');
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
        $chops = $this->memberChopServiceManager->getMemberTotalChops([
            'phone' => $phone
        ]);
        return $this->sendResponse(['chops' => $chops], 'Member retrieved successfully');
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
        $chops = $this->memberChopServiceManager->getMemberChopsDetail([
            'phone' => $phone
        ]);
        return $this->sendResponse($chops, 'Member retrieved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChopsRecords($phone)
    {
        $records = $this->memberChopServiceManager->getMemberChopsRecords([
            'phone' => $phone
        ]);
        return $this->sendResponse(ChopRecord::collection($records), 'Member retrieved successfully');
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
        $orderRecords = $this->transactionManager->listByMemberPhone($phone);
        $orderRecords->load(TransactionConstant::BASIC_RELATIONS);

        return $this->sendResponse($orderRecords, 'Member retrieved successfully');
    }

    public function getBalance($phone)
    {
        $balance = $this->memberPrepaidCardServiceManager->getMemberBalance([
            'phone' => $phone
        ]);
        return $this->sendResponse(['balance' => $balance], 'Member retrieved successfully');
    }

    public function getPrepaidcardRecords($phone)
    {
        $records = $this->memberPrepaidCardServiceManager->getMemberPrepaidCardRecords([
            'phone' => $phone
        ]);
        return $this->sendResponse(PrepaidcardRecord::collection($records), 'Member retrieved successfully');
    }


    public function information($phone)
    {
        $member = $this->memberService->findMemberByPhone($phone);
        $member->load(MemberConstant::ALL_MEMBER_RELATIONS);

        return $this->sendResponse(new Member($member), 'Member retrieved successfully');
    }

    public function detail($id)
    {
        $member = $this->memberService->findMember($id);
        $member->load(MemberConstant::ALL_MEMBER_RELATIONS);

        return $this->sendResponse(new Member($member), 'Member retrieved successfully');
    }
}
