<?php

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HappyR\Google\ApiBundle\Services;
use HappyR\Google\ApiBundle\DependencyInjection;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TODOListBundle:Default:index.html.twig');
    }
}
