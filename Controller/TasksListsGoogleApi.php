<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 09/06/14
 * Time: 01:11
 */

namespace Acme\TODOListBundle\Controller;

use Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class TasksListsGoogleApi extends Controller implements \TasksListsInterface{

    public function getTaskListsAction()
    {

    }

    public function newTaskListAction(Request $request)
    {
        // TODO: Implement newTaskListAction() method.
    }
}