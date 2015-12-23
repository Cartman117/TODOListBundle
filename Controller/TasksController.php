<?php
namespace TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TODOListBundle\Entity\Tasks;
use TODOListBundle\Form\Type\TasksType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TasksController
 * @package TODOListBundle\Controller
 */
class TasksController extends Controller implements TasksInterface
{
    /**
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTasksAction($idTaskList)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $tasks = $repository->findByParent($idTaskList);

        return $this->render('TODOListBundle:Tasks:index.html.twig', array('tasks' => $tasks));
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newTaskAction(Request $request, $idTaskList)
    {
        $task = new Tasks();

        $options = ["update" => FALSE];
        $form = $this->createForm(TasksType::class, $task, $options);

        $form->handleRequest($request);
        if($form->isValid()){
            if (empty($task->getStatus())){
                $task->setStatus("needsAction");
            }
            $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
            $taskList = $repository->findOneById($idTaskList);
            $task->setParent($taskList);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
        }

        return $this->render('TODOListBundle:Tasks:newTaskForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteTaskAction(Request $request, $idTaskList)
    {
        $idTask = $request->request->get('id');

        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $task = $repository->findOneById($idTask);

        if(empty($task)){
            throw $this->createNotFoundException("La tache n'existe pas");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($task);
        $manager->flush();

        return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @param $idTask
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateTaskAction(Request $request, $idTaskList, $idTask)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $task = $repository->findOneById($idTask);

        if(empty($task)){
            throw $this->createNotFoundException("La tâche n'existe pas");
        }

        $options = ["update" => TRUE];
        $form = $this->createForm(TasksType::class, $task, $options);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
        }

        return $this->render('TODOListBundle:Tasks:updateTaskForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @param $idTask
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function statusTaskAction(Request $request, $idTaskList, $idTask)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $task = $repository->findOneById($idTask);

        if(empty($task)){
            throw $this->createNotFoundException("La tache n'existe pas");
        }

        if($task->getStatus() === "completed"){
            $task->setStatus("needsAction");
        }
        else{
            $task->setStatus("completed");
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
    }
}