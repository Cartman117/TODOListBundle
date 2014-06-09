<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 05/06/14
 * Time: 18:26
 */

namespace Acme\TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class OAuthController extends Controller
{
    public function callbackAction(Request $request)
    {
        $code = $request->query->get("code");
        $client = $this->container->get("happyr.google.api.client");

        $googleClient = $client->getGoogleClient();
        $googleClient->setScopes(array(
            'https://www.googleapis.com/auth/tasks'
        ));

        if(!empty($code)){
            $googleClient->authenticate($code);
            $accessToken = $googleClient->getAccessToken();

            $this->securityContext = $this->get("security.context");

            $token = $this->securityContext->getToken(); // get previous token
            $token = new PreAuthenticatedToken(
                $accessToken,
                $token->getCredentials(),
                $token->getProviderKey(),
                [ 'ROLE_HAS_TOKEN' ]
            );
            $_SESSION['token'] = $token;
            $this->securityContext->setToken($token);
        }
        if(isset($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $googleClient->setAccessToken($token->getUser());
        }

        if(!$googleClient->getAccessToken()) {
            $authUrl = $googleClient->createAuthUrl();
            header("Location: ".$authUrl);
            die;
        }
        $service = new  \Google_Service_Tasks($googleClient);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslist"));
    }

    public function exitAction()
    {
        unset($_SESSION['token']);

        return $this->render('TODOListBundle:Default:index.html.twig');
    }
} 