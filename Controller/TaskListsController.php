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
use Acme\TODOListBundle\Controller\TaskListsInterface;
use Symfony\Component\HttpFoundation\Request;


class TaskListsController extends Controller implements TaskListsInterface
{
    public function getTaskListsAction()
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskLists = $repository->findAll();

        return $this->render('TODOListBundle:TaskLists:index.html.twig', ['taskLists' => $taskLists]);
    }

    public function newTaskListAction(Request $request)
    {
        $taskList = new TaskLists();

        $form = $this->createForm(new TaskListsType(), $taskList);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($taskList);
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasklists"));
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ['form' => $form->createView()]);
    }

    public function deleteTaskListAction(Request $request)
    {
        $idTaskList = $request->request->get('idList');

        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskList = $repository->findOneByIdList($idTaskList);

        if(empty($taskList)){
            throw $this->createNotFoundException("La liste de taches n'existe pas");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($taskList);
        $manager->flush();

        return $this->redirect($this->generateUrl("todolist_list_tasklists"));
    }

    public function updateTaskListAction(Request $request, $idTaskList)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskList = $repository->findOneByIdList($idTaskList);

        if(empty($taskList)){
            throw $this->createNotFoundException("La liste de taches n'existe pas");
        }

        $form = $this->createForm(new TaskListsType(), $taskList);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasklists"));
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ['form' => $form->createView()]);
    }
}