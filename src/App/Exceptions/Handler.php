<?php

namespace UserPackage\App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse|\Illuminate\Http\Response|Response
     * @throws ErrorException
     */
    public function render($request, Throwable $exception)
    {
        $response = [];
        $code = $exception->getCode() ?  $exception->getCode() : 500;

        if ($exception instanceof ValidationException) {
            $response['errors'] = $this->validationException($exception);
            $code = 422;
        } elseif ($exception instanceof ConnectionException) {
            throw new ErrorException($exception, $exception->getCode());
        } else {
            $default = [
                'title' => 'Unable to proceed',
                'code' => $code,
                'detail' => $exception->getMessage() ?:
                    'The URI requested is invalid or the resource requested does not exist.'
            ];
            $response['errors'] = [$default];
        }

        app('log')->info(json_encode($response));

        return response()->json($response, $code);
    }
    /**
     * Assign to response attribute the value to ValidationException.
     *
     * @param ValidationException $exception
     * @return array
     */
    public function validationException(ValidationException $exception)
    {
        return $this->jsonApiFormatErrorMessages($exception);
    }
    /**
     * Get formatted errors on standard code, field, message to each field with
     * error.
     *
     * @param  ValidationException $exception
     * @return array
     */
    public function formattedErrors(ValidationException $exception)
    {
        return $this->jsonApiFormatErrorMessages($exception);
    }
    /**
     * @param ValidationException $exception
     * @return array
     */
    public function jsonApiFormatErrorMessages(ValidationException $exception)
    {
        $validationMessages = $this->getTreatedMessages($exception);
        $validationFails = $this->getTreatedFails($exception);
        $errors = [];
        foreach ($validationMessages as $field => $messages) {
            foreach ($messages as $key => $message) {
                $attributes = $this->getValidationAttributes($validationFails, $key, $field);
                $error = [
                    'title'     => 'Unable to proceed',
                    'code'      => $attributes['code'],
                    'detail'    => $message,
                ];
                array_push($errors, $error);
            }
        }
        return $errors;
    }
    public function getValidationAttributes(array $validationFails, $key, $field)
    {
        return [
            'code' => 422,
            'title' => $this->getValidationTitle($validationFails, $key, $field),
        ];
    }
    public function getValidationTitle(array $validationFails, $key, $field)
    {
        return __('exception::exceptions.validation.title', [
            'fails' => array_keys($validationFails[$field])[$key],
            'field' => $field,
        ]);
    }
    /**
     * Get message based on exception type. If exception is generated by
     * $this->validate() from default Controller methods the exception has the
     * response object. If exception is generated by Validator::make() the
     * messages are getted different.
     *
     * @param  Throwable $exception
     * @return array
     */
    public function getTreatedMessages($exception)
    {
        return $this->getMessagesFromValidator($exception);
    }

    public function getMessagesFromValidator($exception)
    {
        return $exception->validator->messages()->messages();
    }

    public function getTreatedFails($exception)
    {
        return $this->getFailsFromValidator($exception);
    }
    public function getFailsFromValidator($exception)
    {
        return $exception->validator->failed();
    }
}
