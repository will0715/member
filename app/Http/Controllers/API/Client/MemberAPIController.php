<?php

namespace App\Http\Controllers\API\Client;

use App\Constants\MemberConstant;
use App\Constants\RecordConstant;
use App\Constants\TransactionConstant;
use App\Http\Controllers\AppBaseController;
use App\ServiceManagers\MemberRegisterManager;
use App\ServiceManagers\MemberChopServiceManager;
use App\ServiceManagers\MemberPrepaidCardServiceManager;
use App\ServiceManagers\TransactionManager;
use App\Http\Requests\API\Client\CreateMemberAPIRequest;
use App\Http\Requests\API\Client\UpdateMemberAPIRequest;
use App\Services\MemberService;
use App\Exceptions\ResourceNotFoundException;
use App\Helpers\AuthMemberHelper;
use App\Http\Resources\Member;
use App\Http\Resources\MemberList;
use App\Http\Resources\Chop;
use App\Http\Resources\ChopRecord;
use App\Http\Resources\PrepaidcardRecord;
use App\Utils\MemberAuthToken;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
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
        $this->memberRegisterManager = app(MemberRegisterManager::class);
        $this->memberChopServiceManager = app(MemberChopServiceManager::class);
        $this->memberPrepaidCardServiceManager = app(MemberPrepaidCardServiceManager::class);
        $this->transactionManager = app(TransactionManager::class);
    }

    public function login(Request $request)
    {
    	$phone = $request->get('phone');
    	$password = $request->get('password');
    	$lineUserId = $request->get('line_user_id');
        try {
            $member = $this->memberService->login([
                'phone' => $phone,
                'password' => $password
            ]);
            if ($lineUserId) {
                $this->memberService->bindMemberLineId($member->id, $lineUserId);
            }
            $token = MemberAuthToken::makeMemberAuthToken($member);

            return $this->sendResponse($token, 'Login successfully');
        } catch(ResourceNotFoundException $e) {
    		return $this->sendError('Member\'s phone not exist', 401);
        } catch (\Exception $e) {
            Log::error($e);
    		return $this->sendError('Unauthenticated', 401);
        }
    }

    public function socialiteLogin(Request $request, $socialiteProvider)
    {
    	$userId = $request->get('user_id');
        try {
            $member = $this->memberService->loginWithSocialite([
                'socialiteProvider' => $socialiteProvider,
                'userId' => $userId
            ]);
            $token = MemberAuthToken::makeMemberAuthToken($member);

            return $this->sendResponse($token, 'Login successfully');
        } catch(ResourceNotFoundException $e) {
    		return $this->sendError('Member\'s socialite is not exist', 401);
        } catch (\Exception $e) {
            Log::error($e);
    		return $this->sendError('Unauthenticated', 401);
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

        $member = $this->memberRegisterManager->registerMember($input);
        return $this->sendResponse(new Member($member), 'Member saved successfully');
    }

    public function information()
    {
        $authMember = $this->getAuthMember();

        $member = $this->memberService->memberBasicInfo($authMember->id);
        $member->load(MemberConstant::SIMPLE_MEMBER_RELATIONS);
        
        return $this->sendResponse(new MemberList($member), 'Member retrieved successfully');
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
    public function update(UpdateMemberAPIRequest $request)
    {
        $authMember = $this->getAuthMember();
        $input = $request->all();

        $member = $this->memberService->updateMember($input, $authMember->id);
        return $this->sendResponse(new Member($member), 'Member updated successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChopsDetail()
    {
        $authMember = $this->getAuthMember();

        $chops = $this->memberChopServiceManager->getMemberChopsDetail([
            'phone' => $authMember->phone
        ]);
        $chops->load(RecordConstant::BRANCH_RELATIONS);

        return $this->sendResponse(Chop::collection($chops), 'Member retrieved successfully');
    }

    /**
     * Display the specified Member.
     * GET|HEAD /members/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function getChopsRecords()
    {
        $authMember = $this->getAuthMember();

        $records = $this->memberChopServiceManager->getMemberChopsRecords([
            'phone' => $authMember->phone
        ]);
        $records->load(RecordConstant::BRANCH_RELATIONS);

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
    public function getOrderRecords()
    {
        $authMember = $this->getAuthMember();

        $orderRecords = $this->transactionManager->listByMemberPhone($authMember->phone);
        $orderRecords->load(TransactionConstant::BASIC_RELATIONS);

        return $this->sendResponse($orderRecords, 'Member retrieved successfully');
    }

    public function getPrepaidcardRecords()
    {
        $authMember = $this->getAuthMember();

        $records = $this->memberPrepaidCardServiceManager->getMemberPrepaidCardRecords([
            'phone' => $authMember->phone
        ]);
        $records->load(RecordConstant::BRANCH_RELATIONS);

        return $this->sendResponse(PrepaidcardRecord::collection($records), 'Member retrieved successfully');
    }

    private function getAuthMember()
    {
        return AuthMemberHelper::getMember();
    }
}
