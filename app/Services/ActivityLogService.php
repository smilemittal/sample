<?php

namespace App\Services;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\ActionLog;

class ActivityLogService
{
    public function index()
    {
        try {
            return ActionLog::with('user')->latest()->paginate(25);;
        } catch (\Exception | RequestException $e) {
            Log::error('activit_log_service', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
