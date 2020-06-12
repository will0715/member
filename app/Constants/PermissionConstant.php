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
        ]
    ];
}