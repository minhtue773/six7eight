<?php
namespace App\Services;

class ResponseAPI
{
  public function responseAPI($success = true, $message = '', $data = [], $statusCode = 200)
  {
    return response()->json([
      'success' => $success,
      'messages' => $message,
      'data' => $data
    ], $statusCode);
  }
}