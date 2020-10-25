<?php

namespace App\Http\Middleware;

use App\Repositories\CustomerRepository;
use App\Services\MemberService;
use App\Utils\MemberAuthToken;
use App\Helpers\AuthMemberHelper;
use Closure;
use Arr;

class MemberAuthJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        if (empty($token)) {
            abort(401, 'Unauthorized');
        }

        $payload = MemberAuthToken::decode($token);
        $memberId = $payload->member_id;
        if (empty($memberId)) {
            abort(401, 'Token is not valid');
        }

        $member = app(MemberService::class)->findMember($memberId);

        if (empty($member)) {
            abort(401, 'Member is not exist or expired');
        }

        $request->merge(['_member' => $member]);
        AuthMemberHelper::setAuthMember($member);

        return $next($request);
    }
}
