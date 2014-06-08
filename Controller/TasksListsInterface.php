<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 09/06/14
 * Time: 01:09
 */
namespace Acme\TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface TasksListsInterface{

    public function getTaskListsAction();

    public function newTaskListAction(Request $request);
}