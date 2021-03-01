<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\AcceptOnlyApplicationJsonException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     *
     */
    public function render($request, Throwable $e) {
        if ($e instanceof ModelNotFoundException) {
                $model = $e->getModel().'';
                $model = mb_substr($model,mb_strrpos($model,'\\') +1);
                $modelRouteId = mb_substr($request->route()->getName(),0,mb_strpos($request->route()->getName(), '.'));
                if (!empty($request->route($modelRouteId))) {
                    return response()->json([
                        'error' => __('exception.resource_not_found', ['id' => $request->route($modelRouteId), 'model' => $model])
                    ], 404);
                }
        }
        return parent::render($request, $e);
    }
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof AcceptOnlyApplicationJsonException) {
                return response()->json([
                    'error' => __('exception.accept_only_application_json')
                ], 406);
            }
            $exception = get_class($e);

            if (env('APP_ENV') == 'production') {
                return response()->json([
                    'error' => in_array($exception ,array_keys(__('exception')))
                    ? __('exception.' . $exception) 
                    : $exception
                ], 404);
            }
        });
    }
}
