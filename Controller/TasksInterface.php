<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 10/06/14
 * Time: 13:58
 */
namespace Acme\TODOListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface TasksInterface{

    public function getTasksAction($idTaskList);

    public function newTaskAction(Request $request, $idTaskList);

    public function deleteTaskAction(Request $request, $idTaskList);

    public function updateTaskAction(Request $request, $idTaskList, $idTask);

    public function statusTaskAction(Request $request, $idTaskList, $idTask);
}