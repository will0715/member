<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\AppBaseController;
use App\Services\MemberService;
use App\Services\MemberResetPasswordService;
use App\Http\Requests\API\Client\ResetPasswordAPIRequest;
use Illuminate\Http\Request;

class ForgotPasswordController extends AppBaseController
{

    public function __construct()
    {
        $this->memberService = app(MemberService::class);
    }

    function sendResetSMS(Request $request)
    {
        $customer = $this->getCustomer($request);
    	$phone = $request->get('phone');
        try {
            $member = $this->memberService->findMemberByPhone($phone);
            $memberResetPasswordService = new MemberResetPasswordService($customer);
            $token = $memberResetPasswordService->sendResetSMS($member);

            return $this->sendSuccess('send reset sms successfully');
        } catch(ResourceNotFoundException $e) {
    		return $this->sendError('Member\'s phone not exist', 401);
        }
    }

    function resetPassword(ResetPasswordAPIRequest $request)
    {
        $customer = $this->getCustomer($request);
    	$token = $request->get('token');
    	$newPassword = $request->get('new_password');
        try {
            $memberResetPasswordService = new MemberResetPasswordService($customer);
            $phone = $memberResetPasswordService->getPhoneNumberByToken($token);
            if (!$phone) {
                return $this->sendError('Token not exist', 400);
            }
            $member = $this->memberService->updateMemberByPhone([
                'password' => $newPassword
            ], $phone);

            return $this->sendSuccess('reset password successfully');
        } catch(ResourceNotFoundException $e) {
    		return $this->sendError('Token not exist', 401);
        }

    }
}
