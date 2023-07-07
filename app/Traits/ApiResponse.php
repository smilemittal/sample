<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * HTTP Status Code
     *
     * @var string
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * Get the HTTP Status Code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the HTTP Status Code
     *
     * @param  string $statusCode
     * @return APIController
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Create Success JSON Response
     *
     * @param  array $data
     * @param  array=[] $headers
     * @return Response
     */
    public function successResponse($data = [], $successCode = 200, $headers = [])
    {
        $data["status"] = true;
        $data = $data + [
            'message' => "Ok",
            'data' => []
        ];
        $data = array_merge(array_flip(array('status', 'message', 'data')), $data);
        $this->setStatusCode($successCode);
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function failResponse($data = [], $errorCode = 500, $headers = [])
    {
        $data["status"] = false;
        $data = $data + [
            'message' => trans('messages.something_went_wrong'),
            'data' => []
        ];
        $errorCode = $errorCode != 0 ? $errorCode : 500;
        $errorCode = ((intval($errorCode) > 599 || intval($errorCode) < 100) ? 500 : intval($errorCode));
        $this->setStatusCode($errorCode);
        $data = array_merge(array_flip(array('status', 'message', 'data')), $data);
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
