<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 09/06/14
 * Time: 01:11
 */

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\TODOListBundle\Controller\TaskListsInterface;
use Symfony\Component\HttpFoundation\Request;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class TaskListsGoogleApiController extends Controller implements TaskListsInterface{

    public function getTaskListsAction()
    {
        $client = $this->container->get("happyr.google.api.client");
        $googleClient = $client->getGoogleClient();
        $token = $_SESSION['token'];
        $googleClient->setAccessToken($token->getUser());
        $service = new  \Google_Service_Tasks($googleClient);
        $taskLists = $service->tasklists->listTasklists();
        return $this->render('TODOListBundle:TaskListsGoogleApi:index.html.twig', ['taskLists' => $taskLists]);
    }

    public function newTaskListAction(Request $request)
    {
        // TODO: Implement newTaskListAction() method.
    }
}