<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 10/06/14
 * Time: 13:57
 */

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\TODOListBundle\Controller\TasksInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Acme\TODOListBundle\Form\Type\TasksType;
use Acme\TODOListBundle\Entity\Tasks;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class TasksGoogleApiController extends Controller implements TasksInterface{

    public function getGoogleServiceTasks()
    {
        $client = $this->container->get("happyr.google.api.client");
        $googleClient = $client->getGoogleClient();
        $this->securityContext = $this->get("security.context");
        $token = $this->securityContext->getToken();
        $googleClient->setAccessToken($token->getUser());

        return new \Google_Service_Tasks($googleClient);
    }

    public function getTasksAction($idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $tasks = $service->tasks->listTasks($idTaskList);

        return $this->render('TODOListBundle:TasksGoogleApi:index.html.twig', ["tasks" => $tasks]);
    }

    public function newTaskAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $task = new \Google_Service_Tasks_Task();

        $form = $this->createForm(new TasksType(false));

        $form->handleRequest($request);
        if($form->isValid()){
            $task->setTitle($form->getData()->getTitle());
            $task->setNotes($form->getData()->getNotes());
            $task->setDue($form->getData()->getDue());
            $task->setParent($idTaskList);
            $service->tasks->insert($idTaskList, $task);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
        }

        return $this->render('TODOListBundle:TasksGoogleApi:newTaskForm.html.twig', ["form" => $form->createView()]);
    }

    public function deleteTaskAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $idTask = $request->request->get('id');
        $service->tasks->delete($idTaskList, $idTask);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
    }

    public function updateTaskAction(Request $request, $idTaskList, $idTask)
    {
        $service = $this->getGoogleServiceTasks();

        $taskGoogleApi = $service->tasks->get($idTaskList, $idTask);

        $task = new Tasks();
        $task->setTitle($taskGoogleApi->getTitle());
        $task->setNotes($taskGoogleApi->getNotes());
        $task->setDue($taskGoogleApi->getDue());

        $form = $this->createForm(new TasksType(true), $task);

        $form->handleRequest($request);
        if($form->isValid()){
            $taskGoogleApi->setTitle($form->getData()->getTitle());
            $taskGoogleApi->setNotes($form->getData()->getNotes());
            $taskGoogleApi->setDue($form->getData()->getDue());
            $service->tasks->update($idTaskList, $idTask ,$taskGoogleApi);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
        }

        return $this->render('TODOListBundle:TasksGoogleApi:updateTaskForm.html.twig', ["form" => $form->createView()]);
    }
}