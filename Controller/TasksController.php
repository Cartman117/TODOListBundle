<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 27/05/14
 * Time: 16:03
 */

namespace Acme\TODOListBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class TasksController extends Controller{

    public function getTasksAction($idList)
    {
        $repository = $this->getDoctrine()->getRepository('TODOListBundle:Tasks');
        $tasks = $repository->findByIdList($idList);

        return $this->render('TODOListBundle:Tasks:index.html.twig', array('tasks' => $tasks));
    }
} 