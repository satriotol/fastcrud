<?php

namespace Satriotol\Fastcrud\Handlers;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Satriotol\Fastcrud\Models\FastcrudErrorLog;

class BetaExceptionHandler extends ExceptionHandler
{
    public function report(Throwable $e): void
    {
        if (env('BETA_MODE', true)) {

            $trace = substr($e->getTraceAsString(), 0, 5000);
            $inputData = request()->except(['password', 'token']);
            $input = substr(json_encode($inputData), 0, 2000);
            try {
                FastcrudErrorLog::create([
                    'message'     => $e->getMessage(),
                    'trace'       => $trace,
                    'error_code'  => $e->getCode(),

                    'url'         => request()->fullUrl(),
                    'method'      => request()->method(),
                    'input'       => $input,

                    'user_id'     => auth()->id(),
                    'ip_address'  => request()->ip(),
                    'user_agent'  => request()->userAgent(),

                    'environment' => app()->environment(),
                    'level'       => 'error',
                    'status'      => 0,
                ]);
            } catch (\Throwable $ignored) {
            }
        }

        parent::report($e);
    }
}
