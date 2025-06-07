<?php
// filepath: /home/dirham/Downloads/mip-backend/app/Helpers/LoggerHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Throwable;

class LoggerHelper
{
    public static function logError(Throwable $e): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $backtrace[1] ?? null;

        $controller = $caller['class'] ?? 'UnknownController';
        $action = $caller['function'] ?? 'unknownMethod';

        $log = "\n" . <<<LOG
==================== ERROR LOG ====================
Controller : {$controller}
Action     : {$action}
Message    : {$e->getMessage()}
File       : {$e->getFile()} : {$e->getLine()}
===================================================
LOG;

        Log::error($log);
    }
}
