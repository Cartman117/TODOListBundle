<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 10/06/14
 * Time: 13:58
 */
namespace Acme\TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface TasksInterface
 * @package Acme\TODOListBundle\Controller
 */
interface TasksInterface
{
    /** Get Tasks */
    public function getTasksAction($idTaskList);

    /** Create a new Task */
    public function newTaskAction(Request $request, $idTaskList);

    /** Delete a Task */
    public function deleteTaskAction(Request $request, $idTaskList);

    /** Update a Task */
    public function updateTaskAction(Request $request, $idTaskList, $idTask);

    /** Change the status of a Task */
    public function statusTaskAction(Request $request, $idTaskList, $idTask);
}