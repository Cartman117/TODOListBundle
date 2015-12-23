<?php
namespace TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TODOListBundle\Controller\TasksInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use TODOListBundle\Form\Type\TasksType;
use TODOListBundle\Entity\Tasks;
use HappyR\Google\ApiBundle\Services\GoogleClient;

/**
 * Class TasksGoogleApiController
 * @package TODOListBundle\Controller
 */
class TasksGoogleApiController extends Controller implements TasksInterface
{
    /**
     * @return \Google_Service_Tasks
     */
    public function getGoogleServiceTasks()
    {
        $client = $this->container->get("happyr.google.api.client");
        $googleClient = $client->getGoogleClient();
        $this->securityContext = $this->get("security.token_storage");
        $token = $this->securityContext->getToken();
        $googleClient->setAccessToken($token->getUser());

        return new \Google_Service_Tasks($googleClient);
    }

    /**
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTasksAction($idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $tasks = $service->tasks->listTasks($idTaskList);

        return $this->render('TODOListBundle:TasksGoogleApi:index.html.twig', ["tasks" => $tasks]);
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newTaskAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $task = new \Google_Service_Tasks_Task();

        $options = ["update" => FALSE];
        $form = $this->createForm(TasksType::class, NULL, $options);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $task->setTitle($form->getData()->getTitle());
            $task->setNotes($form->getData()->getNotes());
            $task->setDue($form->getData()->getDue());
            $task->setParent($idTaskList);
            $service->tasks->insert($idTaskList, $task);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
        }

        return $this->render('TODOListBundle:TasksGoogleApi:newTaskForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTaskAction(Request $request, $idTaskList)
    {
        $service = $this->getGoogleServiceTasks();

        $idTask = $request->request->get('id');
        $service->tasks->delete($idTaskList, $idTask);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @param $idTask
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateTaskAction(Request $request, $idTaskList, $idTask)
    {
        $service = $this->getGoogleServiceTasks();

        $taskGoogleApi = $service->tasks->get($idTaskList, $idTask);

        $task = new Tasks();
        $task->setTitle($taskGoogleApi->getTitle());
        $task->setNotes($taskGoogleApi->getNotes());
        $due = new \DateTime();
        if (!empty($taskGoogleApi->getDue())) {
            $due = \DateTime::createFromFormat("Y-m-d\TH:i:s.000\Z", $taskGoogleApi->getDue());
        }
        $task->setDue($due);

        $options = ["update" => TRUE];
        $form = $this->createForm(TasksType::class, $task, $options);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $taskGoogleApi->setTitle($form->getData()->getTitle());
            $taskGoogleApi->setNotes($form->getData()->getNotes());
            $taskGoogleApi->setDue($form->getData()->getDue()->format("Y-m-d\TH:i:s.000\Z"));
            $service->tasks->update($idTaskList, $idTask ,$taskGoogleApi);

            return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
        }

        return $this->render('TODOListBundle:TasksGoogleApi:updateTaskForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @param $idTask
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function statusTaskAction(Request $request, $idTaskList, $idTask)
    {
        $service = $this->getGoogleServiceTasks();

        $taskGoogleApi = $service->tasks->get($idTaskList, $idTask);
        if ($taskGoogleApi->getStatus() === "completed") {
            $taskGoogleApi->setStatus("needsAction");
            $taskGoogleApi->setCompleted(null);
        } else {
            $taskGoogleApi->setStatus("completed");
        }
        $service->tasks->update($idTaskList, $idTask ,$taskGoogleApi);

        return $this->redirect($this->generateUrl("todolist_googleapi_list_tasks", ["idTaskList" => $idTaskList]));
    }
}