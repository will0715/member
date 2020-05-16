<?php
namespace App\Constants;

class MemberConstant {
    const BASE_MEMBER_RELATIONS = [
        'rank', 
        'chops'
    ];
    
    const ALL_MEMBER_RELATIONS = [
        'rank', 
        'chops', 
        'chops.branch', 
        'orderRecords', 
        'orderRecords.branch', 
        'orderRecords.transactionItems', 
        'orderRecords.chopRecords', 
        'chopRecords', 
        'chopRecords.branch', 
        'chopRecords.voidRecord', 
        'prepaidcard', 
        'prepaidcardRecords',
        'prepaidcardRecords.branch', 
        'prepaidcardRecords.voidRecord'
    ];
}