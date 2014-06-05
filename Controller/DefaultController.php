<?php

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HappyR\Google\ApiBundle\Services;
use HappyR\Google\ApiBundle\DependencyInjection;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $client = $this->container->get("happyr.google.api.client");

        $googleClient = $client->getGoogleClient();
        $googleClient->setScopes(array(
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ));

        $service = new \Google_Service_Tasks($googleClient);
        if (isset($_GET['logout'])) { // logout: destroy token
            unset($_SESSION['token']);
            die('Logged out.');
        }

        if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
            $googleClient->authenticate($_GET['code']);
            $_SESSION['token'] = $client->getAccessToken();
        }

        if (isset($_SESSION['token'])) { // extract token from session and configure client
            $token = $_SESSION['token'];
            $googleClient->setAccessToken($token);
        }

        if (!$googleClient->getAccessToken()) { // auth call to google
            $authUrl = $googleClient->createAuthUrl();
            header("Location: ".$authUrl);
            die;
        }
        return $this->render('TODOListBundle:Default:index.html.twig');
    }
}
