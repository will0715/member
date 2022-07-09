<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $others = [])
    {
        return Response::json(array_merge([
            'success' => true,
            'message' => $message,
            'data' => $result,
        ], $others), 200);
    }

    public function sendResponseWithTotalCount($result, $message, $totalCount = 0)
    {
        return $this->sendResponse($result, $message, [
            'totalCount' => $totalCount
        ]);
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public function getCustomer($request)
    {
        return $request->_customer;
    }

    public function getCustomerName($request)
    {
        return $request->_customerName;
    }
}
