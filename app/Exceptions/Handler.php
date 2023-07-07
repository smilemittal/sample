<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (config('app.env') != 'local' && app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    // public function report(Throwable $exception)
    // {
    //     if (!App::environment('local') && app()->bound('sentry')) {
    //         if ($user = request()->user()) {
    //             \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($user) {
    //                 $scope->setUser([
    //                         'id' => $user->id,
    //                         'email' => $user->email,
    //                     ]
    //                 );
    //             });
    //         }
    //         app('sentry')->captureException($exception);
    //     }
    //     parent::report($exception);
    // }
}
