<?php
namespace Acme\TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface TaskListsInterface
 * @package Acme\TODOListBundle\Controller
 */
interface TaskListsInterface
{
    /** Get TaskLists */
    public function getTaskListsAction();

    /** Create a TaskList */
    public function newTaskListAction(Request $request);

    /** Delete a TaskList */
    public function deleteTaskListAction(Request $request);

    /** Update a TaskList */
    public function updateTaskListAction(Request $request, $idTaskList);
}