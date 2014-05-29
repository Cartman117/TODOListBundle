<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 27/05/14
 * Time: 16:03
 */

namespace Acme\TODOListBundle\Controller;
use Acme\TODOListBundle\Entity\Tasklists;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;


class TaskListsController extends Controller{

    public function getTaskListsAction() {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:TaskLists');
        $taskLists = $repository->findAll();

        return $this->render('TODOListBundle:TaskLists:index.html.twig', ['taskLists' => $taskLists]);
    }

    public function newTaskListAction(Request $request) {

        $taskList = new Tasklists();

        $form = $this->createFormBuilder()
            ->add('name')
            ->add('Créer', 'submit')
            ->getForm();

        if($form->handleRequest($request)->isValid()){
            $this->getDoctrine()->getManager()->persist($taskList);
        }

        return $this->render('TODOListBundle:TaskLists:newTaskListForm.html.twig', ["form" => $form->createView()]);
    }
} 