<?php
namespace App\Constants;

class MemberConstant {
    const SIMPLE_MEMBER_RELATIONS = [
        'rank',
        'registerBranch'
    ];

    const BASE_MEMBER_RELATIONS = [
        'rank', 
        'chops', 
        'prepaidcard'
    ];
    
    const ALL_MEMBER_RELATIONS = [
        'rank', 
        'chops', 
        'chops.branch', 
        'orderRecords', 
        'orderRecords.branch', 
        'orderRecords.transactionItems', 
        'orderRecords.transactionItems.condiments', 
        'chopRecords', 
        'chopRecords.branch', 
        'chopRecords.voidRecord', 
        'prepaidcard', 
        'prepaidcardRecords',
        'prepaidcardRecords.branch', 
        'prepaidcardRecords.voidRecord',
        'registerBranch'
    ];
}