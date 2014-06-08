<?php

namespace Acme\TODOListBundle\Security\Authorization;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $client;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client->getGoogleClient();
        $this->client->setScopes(array(
            'https://www.googleapis.com/auth/tasks'
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
       return new RedirectResponse($this->client->createAuthUrl());
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $message = 'Erreur: ' . $exception->getMessage() . ' avec le code: ' . $exception->getCode();

        $response = new Response();
        $response->setContent($message);

        //400 : session vidée
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }
}