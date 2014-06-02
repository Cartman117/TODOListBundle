<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 27/05/14
 * Time: 16:03
 */

namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\TODOListBundle\Entity\TaskLists;
use Acme\TODOListBundle\Form\Type\TaskListsType;
use Symfony\Component\HttpFoundation\Request;


class TaskListsController extends Controller{

    public function getTaskListsAction() {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskLists = $repository->findAll();

        return $this->render('TODOListBundle:TaskLists:index.html.twig', ['taskLists' => $taskLists]);
    }

    public function newTaskListAction(Request $request) {
        $taskList = new TaskLists();

        $form = $this->createForm(new TaskListsType(), $taskList);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($taskList);
            $manager->flush();

            return $this->redirect($this->generateUrl("todo_list_tasklists"));
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ["form" => $form->createView()]);
    }

    public function deleteTaskListAction($idTaskList){
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskList = $repository->findOneByIdList($idTaskList);

        if(!empty($taskList)){
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($taskList);
            $manager->flush();
        }

        return $this->redirect($this->generateUrl("todo_list_tasklists"));
    }

    public function updateTaskList(){
        
    }
}