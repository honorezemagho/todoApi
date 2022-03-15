<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;

/**
 * @SWG\Swagger(
 *   basePath="/api/",
 *   @SWG\Info(
 *     title="Todo API Documentation",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    protected const ERROR_500_MESSAGE = 'Une erreur est survenue, veuillez reessayer plus tard';

    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
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

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($result, $message, $statusCode, $status)
    {
        $response = [
            'success' => true,
            'statusCode' => $statusCode,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, $status);
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($message, $statusCode, $status, $data = [])
    {
        $response = [
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            "data"=>$data
        ];


        return response()->json($response, $status);
    }
}
