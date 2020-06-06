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
        'transactionItems', 
        'transactionItems.condiments'
    ];

    const DETAIL_RELATIONS = [
        'branch', 
        'member',
        'transactionItems', 
        'transactionItems.condiments'
    ];
}