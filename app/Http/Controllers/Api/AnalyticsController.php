<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Analytics en desarrollo',
            'data' => [],
        ]);
    }

    public function sales()
    {
        return response()->json([
            'success' => true,
            'message' => 'Sales analytics en desarrollo',
            'data' => [],
        ]);
    }

    public function products()
    {
        return response()->json([
            'success' => true,
            'message' => 'Product analytics en desarrollo',
            'data' => [],
        ]);
    }
}