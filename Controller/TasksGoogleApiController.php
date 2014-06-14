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
use Acme\TODOListBundle\Form\Type\TasksGoogleApiType;
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

        $form = $this->createForm(new TasksGoogleApiType());

        $form->handleRequest($request);
        if($form->isValid()){
            $task->setTitle($form->getData()["title"]);
            $task->setNotes($form->getData()["notes"]);
            $task->setDue($form->getData()["due"]);
            $task->setParent($idTaskList);
            $service->tasks->insert($idTaskList, $task);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
        }

        return $this->render('TODOListBundle:TasksGoogleApi:newTaskForm.html.twig', ["form" => $form->createView()]);
    }

    public function deleteTaskAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $idTask = $request->request->get('idTask');
        $service->tasks->delete($idTaskList, $idTask);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
    }

    public function updateTaskAction(Request $request, $idTaskList, $idTask)
    {
        $service = $this->getGoogleServiceTasks();

        $tasks = $service->tasks->listTasks($idTaskList);
        $finalTasks = [-1 => "Do not move"];
        foreach($tasks as $t){
            if($t->getId() != $idTask){
                $finalTasks[$t->getId()] = $t->getTitle();
            }
            else{
                $task = $t;
            }
        }

        $form = $this->createFormBuilder()
            ->add('title', 'text', ['data' => $task->getTitle()])
            ->add('notes', 'textarea', ['data' => $task->getNotes()])
            ->add('due', 'datetime', ['data' => $task->getDue()])
            ->add('newIdTask', 'choice', ['choices' => $finalTasks])
            ->add('Update', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if($form->isValid()){
            $task->setTitle($form->getData()["title"]);
            $task->setNotes($form->getData()["notes"]);
            $task->setDue($form->getData()["due"]);
            $service->tasks->update($idTaskList, $idTask ,$task);
            if($form->getData()["newIdTask"] !== -1){
                $service->tasks->move($idTaskList, $idTask, ["parent" => $form->getData()["newIdTaskList"]]);
            }

            return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
        }

        return $this->render('TODOListBundle:TasksGoogleApi:updateTaskForm.html.twig', ["form" => $form->createView()]);
    }
}