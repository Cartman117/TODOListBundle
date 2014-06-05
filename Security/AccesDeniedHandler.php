<?php

namespace Acme\TODOListBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use HappyR\Google\ApiBundle\Services;
use HappyR\Google\ApiBundle\DependencyInjection;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $client;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new RedirectResponse($this->client->generateAuthUrl());
    }
}