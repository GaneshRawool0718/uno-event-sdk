<?php

namespace Uno\EventSdk\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Uno\EventSdk\Exception\EventDispatchException;
use Uno\EventSdk\Exception\InvalidEventException;
use Symfony\Component\HttpKernel\Exception\{BadRequestHttpException,UnauthorizedHttpException,
AccessDeniedHttpException,NotFoundHttpException,MethodNotAllowedHttpException,
ConflictHttpException,UnsupportedMediaTypeHttpException,UnprocessableEntityHttpException};

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $status = 500;

        switch (true) {
            case $e instanceof InvalidEventException:
            case $e instanceof BadRequestHttpException:
                $status = 400; break;
            case $e instanceof UnauthorizedHttpException:
                $status = 401; break;
            case $e instanceof AccessDeniedHttpException:
                $status = 403; break;
            case $e instanceof NotFoundHttpException:
                $status = 404; break;
            case $e instanceof MethodNotAllowedHttpException:
                $status = 405; break;
            case $e instanceof ConflictHttpException:
                $status = 409; break;
            case $e instanceof UnsupportedMediaTypeHttpException:
                $status = 415; break;
            case $e instanceof UnprocessableEntityHttpException:
                $status = 422; break;
        }

        $event->setResponse(new JsonResponse([
            'status' => 'error',
            'message' => $e->getMessage()
        ], $status));
    }
}
