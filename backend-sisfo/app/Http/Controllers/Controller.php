<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse(string $message, mixed $data = [], int $status = 200, mixed $errors = [])
    {
        $response = [
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if (config('app.debug')) {
            $response['errors'] = $errors;
        }

        if ($status < 100 || $status > 599) {
            $status = 500;
        }

        return response()->json($response, $status);
    }
}
