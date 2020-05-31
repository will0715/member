<?php
namespace App\Constants;

class TransactionConstant {
    const BASIC_RELATIONS = [
        'branch', 
        'member',
        'transactionItems',
        'transactionItems.condiments'
    ];

    const WITH_CHPOS_RELATIONS = [
        'chopRecords', 
        'chopRecords.earnChopRule', 
        'transactionItems', 
        'transactionItems.condiments'
    ];

    const DETAIL_RELATIONS = [
        'branch', 
        'member',
        'chopRecords', 
        'chopRecords.earnChopRule', 
        'transactionItems', 
        'transactionItems.condiments'
    ];
}