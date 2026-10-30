<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /** Liveness deliberately avoids dependencies so a temporary DB outage does not restart every Pod. */
    public function health(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }

    public function ready(): JsonResponse
    {
        $checks = [];
        try {
            DB::connection()->getPdo();
            $checks['relational'] = 'ok';
        } catch (\Throwable) {
            $checks['relational'] = 'failed';
        }

        try {
            DB::connection('mongodb')->getMongoDB()->command(['ping' => 1]);
            $checks['mongodb'] = 'ok';
        } catch (\Throwable) {
            $checks['mongodb'] = 'failed';
        }

        $ready = !in_array('failed', $checks, true);
        return response()->json(['status' => $ready ? 'ready' : 'not_ready', 'checks' => $checks], $ready ? 200 : 503);
    }
}
