<?php
namespace Acme\TODOListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\TODOListBundle\Entity\TaskLists;
use Acme\TODOListBundle\Form\Type\TaskListsType;
use Acme\TODOListBundle\Controller\TaskListsInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskListsController
 * @package Acme\TODOListBundle\Controller
 */
class TaskListsController extends Controller implements TaskListsInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl("todolist_list_tasklists"));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTaskListsAction()
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskLists = $repository->findAll();

        return $this->render('TODOListBundle:TaskLists:index.html.twig', ['taskLists' => $taskLists]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteTaskListAction(Request $request)
    {
        $idTaskList = $request->request->get('id');

        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskList = $repository->findOneById($idTaskList);

        if(empty($taskList)){
            throw $this->createNotFoundException("La liste de taches n'existe pas");
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($taskList);
        $manager->flush();

        return $this->redirect($this->generateUrl("todolist_list_tasklists"));
    }

    /**
     * @param Request $request
     * @param $idTaskList
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateTaskListAction(Request $request, $idTaskList)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskList = $repository->findOneById($idTaskList);

        if(empty($taskList)){
            throw $this->createNotFoundException("La liste de taches n'existe pas");
        }

        $form = $this->createForm(new TaskListsType(true), $taskList);

        $form->handleRequest($request);
        if($form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirect($this->generateUrl("todolist_list_tasklists"));
        }

        return $this->render('TODOListBundle:TaskLists:updateTaskListForm.html.twig', ['form' => $form->createView()]);
    }
}