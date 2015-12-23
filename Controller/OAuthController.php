<?php
namespace TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use HappyR\Google\ApiBundle\Services\GoogleClient;

/**
 * Class OAuthController
 * @package TODOListBundle\Controller
 */
class OAuthController extends Controller
{
    /**
     * Callback for GoogleApi
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function callbackAction(Request $request)
    {
        $code = $request->query->get("code");
        $client = $this->container->get("happyr.google.api.client");

        $googleClient = $client->getGoogleClient();
        $googleClient->setScopes([
            'https://www.googleapis.com/auth/tasks'
        ]);

        if(!empty($code)){
            $googleClient->authenticate($code);
            $accessToken = $googleClient->getAccessToken();

            $this->securityContext = $this->get("security.token_storage");

            $token = $this->securityContext->getToken();
            if ($token instanceof AnonymousToken){
                $token = new PreAuthenticatedToken(
                    $accessToken,
                    $token->getCredentials(),
                    $token->getSecret(),
                    [ 'ROLE_HAS_TOKEN' ]
                );
            } else {
                $token = new PreAuthenticatedToken(
                    $accessToken,
                    $token->getCredentials(),
                    $token->getProviderKey(),
                    [ 'ROLE_HAS_TOKEN' ]
                );
            }
            $this->securityContext->setToken($token);
        }
        if(!empty($this->securityContext)) {
            $token = $this->securityContext->getToken();
            $googleClient->setAccessToken($token->getUser());
        }

        if(!$googleClient->getAccessToken()) {
            $authUrl = $googleClient->createAuthUrl();
            header("Location: ".$authUrl);
            die;
        }

        return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslists"));
    }

    /**
     * Exit
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function exitAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        return $this->redirect($this->generateUrl("todolist_homepage"));
    }
} 