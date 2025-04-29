<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working fine.'
        ]);
    }

}
