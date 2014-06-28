<?php

namespace Acme\TODOListBundle\Security\Authorization;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $client;
    private $router;

    public function __construct(GoogleClient $client, RouterInterface $router)
    {
        $this->router = $router;
        $this->client = $client->getGoogleClient();
        $this->client->setScopes([
            'https://www.googleapis.com/auth/tasks'
        ]);
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

        if ($exception instanceof \Google_Service_Exception) {
            $response = new RedirectResponse("http://localhost/");
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode());

            $event->setResponse($response);
        }
        if ($exception instanceof \Google_Auth_Exception) {
            $this->handle(new Request(), new AccessDeniedException());
        }
    }
}