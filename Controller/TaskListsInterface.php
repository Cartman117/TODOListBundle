<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 09/06/14
 * Time: 01:09
 */
namespace Acme\TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface TaskListsInterface{

    public function getTaskListsAction();

    public function newTaskListAction(Request $request);

    public function deleteTaskListAction(Request $request);
}