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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class TaskListsGoogleApiController extends Controller implements TaskListsInterface{

    public function getGoogleServiceTasks()
    {
        $client = $this->container->get("happyr.google.api.client");
        $googleClient = $client->getGoogleClient();
        $this->securityContext = $this->get("security.context");
        $token = $this->securityContext->getToken();
        $googleClient->setAccessToken($token->getUser());

        return new \Google_Service_Tasks($googleClient);
    }

    public function getTaskListsAction()
    {
        $service = $this->getGoogleServiceTasks();

        $taskLists = $service->tasklists->listTasklists();

        return $this->render('TODOListBundle:TaskListsGoogleApi:index.html.twig', ['taskLists' => $taskLists]);
    }

    public function newTaskListAction(Request $request)
    {
        $serviceTask = $this->getGoogleServiceTasks();

        $taskList = new \Google_Service_Tasks_TaskList();

        $form = $this->createFormBuilder()
            ->add("title")
            ->add("Create", "submit")
            ->getForm();

        $form->handleRequest($request);
        if($form->isValid()){
            $taskList->setTitle($form->getData()['title']);
            $serviceTask->tasklists->insert($taskList);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslist"));
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ['form' => $form->createView()]);
    }

    public function deleteTaskListAction(Request $request)
    {
        $service = $this->getGoogleServiceTasks();

        $idTaskList = $request->request->get('idTaskList');
        $service->tasklists->delete($idTaskList);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslist"));
    }

    public function updateTaskListAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $taskList = $service->tasklists->get($idTaskList);
        $form = $this->createFormBuilder()
            ->add("title", "text", ['data' => $taskList->getTitle()])
            ->add("Update", "submit")
            ->getForm();

        $form->handleRequest($request);
        if($form->isValid()){
            $taskList->setTitle($form->getData()['title']);
            $service->tasklists->update($taskList->getId(), $taskList);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslist"));
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ['form' => $form->createView()]);
    }
}