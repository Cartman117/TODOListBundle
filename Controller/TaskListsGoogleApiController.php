<?php
namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\TODOListBundle\Controller\TaskListsInterface;
use Acme\TODOListBundle\Form\Type\TaskListsType;
use Symfony\Component\HttpFoundation\Request;
use HappyR\Google\ApiBundle\Services\GoogleClient;

/**
 * Class TaskListsGoogleApiController
 * @package Acme\TODOListBundle\Controller
 */
class TaskListsGoogleApiController extends Controller implements TaskListsInterface
{
    /**
     * @return \Google_Service_Tasks
     */
    public function getGoogleServiceTasks()
    {
        $client = $this->container->get("happyr.google.api.client");
        $googleClient = $client->getGoogleClient();
        $this->securityContext = $this->get("security.context");
        $token = $this->securityContext->getToken();
        $googleClient->setAccessToken($token->getUser());

        return new \Google_Service_Tasks($googleClient);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTaskListsAction()
    {
        $service = $this->getGoogleServiceTasks();

        $taskLists = $service->tasklists->listTasklists();

        return $this->render('TODOListBundle:TaskListsGoogleApi:index.html.twig', ['taskLists' => $taskLists]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newTaskListAction(Request $request)
    {
        $serviceTask = $this->getGoogleServiceTasks();

        $taskList = new \Google_Service_Tasks_TaskList();

        $form = $this->createForm(new TaskListsType(false, '\Google_Service_Tasks_TaskList'));

        $form->handleRequest($request);
        if($form->isValid()){
            $taskList->setTitle($form->getData()->getTitle());
            $serviceTask->tasklists->insert($taskList);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslists"));
        }

        return $this->render('TODOListBundle:TaskListsGoogleApi:newTaskListForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTaskListAction(Request $request)
    {
        $service = $this->getGoogleServiceTasks();

        $idTaskList = $request->request->get('id');
        $service->tasklists->delete($idTaskList);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslists"));
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateTaskListAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $taskList = $service->tasklists->get($idTaskList);

        $form = $this->createForm(new TaskListsType(true, '\Google_Service_Tasks_TaskList'), $taskList);

        $form->handleRequest($request);
        if($form->isValid()){
            $service->tasklists->update($taskList->getId(), $taskList);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_taskslists"));
        }

        return $this->render('TODOListBundle:TaskListsGoogleApi:updateTaskListForm.html.twig', ['form' => $form->createView()]);
    }
}