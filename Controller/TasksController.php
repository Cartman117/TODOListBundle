<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 27/05/14
 * Time: 16:03
 */

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\TODOListBundle\Entity\Tasks;
use Acme\TODOListBundle\Form\Type\TasksType;
use Symfony\Component\HttpFoundation\Request;


class TasksController extends Controller{

    public function getTasksAction($idTaskList)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $tasks = $repository->findByIdList($idTaskList);

        return $this->render('TODOListBundle:Tasks:index.html.twig', array('tasks' => $tasks));
    }


    public function newTaskAction(Request $request, $idTaskList) {
        $task = new Tasks();

        $form = $this->createForm(new TasksType(), $task);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
        }

        return $this->render('TODOListBundle:Tasks:newTaskForm.html.twig', ["form" => $form->createView()]);
    }

    public function deleteTaskAction(Request $request, $idTaskList){
        $idTask = $request->request->get('idTask');

        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $task = $repository->findOneByIdTask($idTask);

        if(empty($task)){
            throw $this->createNotFoundException("La tache n'existe pas");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($task);
        $manager->flush();

        return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
    }

    public function updateTaskAction($idTask, $idTaskList, Request $request){
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $task = $repository->findOneByIdTask($idTask);

        if(empty($task)){
            throw $this->createNotFoundException("La tache n'existe pas");
        }

        $form = $this->createForm(new TasksType(), $task);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasks", ['idTaskList' => $idTaskList]));
        }

        return $this->render('TODOListBundle:Tasks:newTaskForm.html.twig', ["form" => $form->createView()]);
    }
} 