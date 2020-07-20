<?php

namespace App\Http\Controllers\API\Client;

use App\Constants\MemberConstant;
use App\Http\Controllers\AppBaseController;
use App\ServiceManagers\MemberRegisterManager;
use App\Http\Requests\API\Client\CreateMemberAPIRequest;
use App\Http\Resources\Member;
use App\Services\MemberService;
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
}
