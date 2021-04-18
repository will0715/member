<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\AppBaseController;
use App\Services\ConfigService;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Http\Request;
use Response;
use Log;

/**
 * Class ConfigAPIController
 * @package App\Http\Controllers
 */

class ConfigAPIController extends AppBaseController
{

    public function __construct()
    {
        $this->configService = app(ConfigService::class);
    }

    public function lineLiff(Request $request)
    {
        $lineLiff = $this->configService->getValue('LINE_LIFF');

        return $this->sendResponse([
            'line_liff_id' => $lineLiff
        ], 'get line liff successfully');
    }
}
