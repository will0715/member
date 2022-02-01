<?php

namespace App\Services;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Constants\MemberConstant;
use App\Criterias\LimitOffsetCriteria;
use App\Repositories\MemberRepository;
use App\Repositories\RankRepository;
use App\Repositories\SMSLogRepository;
use App\Helpers\CustomerHelper;
use App\Events\MemberRegistered;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\SearchFieldEmptyException;
use App\Models\Member;
use Poyi\PGSchema\Facades\PGSchema;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Twilio\Rest\Client as TwilioClient;
use Arr;
use Auth;
use Cache;
use Hash;

class MemberResetPasswordService
{
    const TOKEN_EXPIRED_TIME = 60 * 30;
    
    /** @var  MemberRepository */
    private $memberRepository;
    private $customer;

    public function __construct($customer)
    {
        $this->memberRepository = app(MemberRepository::class);
        $this->smsLogRepository = app(SMSLogRepository::class);
        $this->customer = $customer;
    }

    public function setResetToken(Member $member, $token)
    {
        $customerName = $this->customer->name;
        $phone = $member->phone;

        Cache::put($customerName . $token, $phone, self::TOKEN_EXPIRED_TIME);
    }

    public function getPhoneNumberByToken($token)
    {
        $customerName = $this->customer->name;

        return Cache::get($customerName . $token);
    }
    
    public function generateResetToken()
    {
        return Str::random(32);
    }

    public function sendResetMail($member)
    {
        // TODO: Send mail
    }

    public function sendResetSMS($member)
    {
        $token = $this->generateResetToken();
        $customerName = $this->customer->name;
        $phone = $member->phone;

        $this->setResetToken($member, $token);
        $value = Cache::get($customerName . $token);
        $resetPasswordPath = "https://{$customerName}.client.memberline.com.tw/resetPassword?token={$token}";

        $twilioClient = app(TwilioClient::class);
        $twilioMessagingServiceSid = $this->getMessagingServiceSid();

        $message = $twilioClient->messages->create(
            $this->getPhoneNumberWithNationalCode($phone),
            [
                'messagingServiceSid' => $twilioMessagingServiceSid,
                'body' => '感謝您使用 Memberline。請於 30 分鐘內重置密碼。' . $resetPasswordPath
            ]
        );

        $log = $this->smsLogRepository->create([
            'phone' => $phone,
            'content' => [
                'sid' =>$message->sid
            ]
        ]);

        return $token;
    }

    private function getMessagingServiceSid()
    {
        return config('sms.twilio.messaging_service_sid');
    }

    private function getPhoneNumberWithNationalCode($phone)
    {
        return '+886' . $phone;
    }
}
