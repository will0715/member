<?php
namespace App\Constants;

class PermissionConstant {
    const ALL_PERMISSIONS = [
        'super-admin' => [
            'super-admin'
        ],
        'user' => [
            'view-user',
            'edit-user'
        ],
        'member' => [
            'view-member',
            'edit-member'
        ],
        'rank' => [
            'view-rank',
            'edit-rank'
        ],
        'branch' => [
            'view-branch',
            'edit-branch'
        ],
        'rule' => [
            'view-rule',
            'edit-rule'
        ],
        'chops' => [
            'view-chops',
            'edit-chops'
        ],
        'prepaidcard' => [
            'view-prepaidcard',
            'edit-prepaidcard'
        ],
        'promotion' => [
            'view-promotion',
            'edit-promotion'
        ],
        'report' => [
            'view-report',
            'edit-report'
        ],
        'pickup-coupon' => [
            'view-pickup-coupon',
            'edit-pickup-coupon'
        ],
        'register-chop-rule' => [
            'view-register-chop-rule',
            'edit-register-chop-rule'
        ],
    ];
}