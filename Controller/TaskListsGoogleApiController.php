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
        $client = $this->container->get("happyr.google.api.client");
        $googleClient = $client->getGoogleClient();
        $token = $_SESSION['token'];
        $googleClient->setAccessToken($token->getUser());
        $service = new \Google_Service_Tasks($googleClient);

        $taskList = new \Google_Service_Tasks_TaskList();

        $form = $this->createFormBuilder()
            ->add("title")
            ->add("Créer", "submit")
            ->getForm();

        $form->handleRequest($request);
        if($form->isValid()){
            $taskList->setTitle($form->getData()['title']);
            $service->tasklists->insert($taskList);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslist"));
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ['form' => $form->createView()]);
    }
}