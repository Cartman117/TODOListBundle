<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 05/06/14
 * Time: 18:26
 */

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HappyR\Google\ApiBundle\Services;
use HappyR\Google\ApiBundle\DependencyInjection;

class OAuthController
{
    public function callbackAction($code)
    {
        $client = $this->container->get("happyr.google.api.client");

        $googleClient = $client->getGoogleClient();
        $googleClient->setScopes(array(
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ));

        if(!empty($code)){
            $googleClient->authenticate($code);
            $_SESSION['token'] = $client->getAccessToken();
        }

        if(isset($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $googleClient->setAccessToken($token);
        }

        if(!$googleClient->getAccessToken()) {
            $authUrl = $googleClient->createAuthUrl();
            header("Location: ".$authUrl);
            die;
        }

        $service = new \Google_Service_Tasks($googleClient);

        return $this->render('TODOListBundle:Default:index.html.twig');
    }

    public function exitAction()
    {
        unset($_SESSION['token']);

        return $this->render('TODOListBundle:Default:index.html.twig');
    }
} 