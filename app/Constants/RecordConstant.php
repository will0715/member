<?php
namespace App\Constants;

class RecordConstant {
    const BASIC_RELATIONS = [
        'branch:id,code,name', 
        'member:id,phone,first_name,last_name',
    ];
    
    const BRANCH_RELATIONS = [
        'branch:id,code,name', 
    ];
}