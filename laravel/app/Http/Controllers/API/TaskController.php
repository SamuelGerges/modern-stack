<?php

namespace App\Http\Controllers\API;

use App\Events\TaskCompleted;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Task\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }


    public function getAllTasks()
    {
        $tasks = $this->taskService->getAllTasks();
        $data  = [
            'tasks' => TaskResource::collection($tasks),
            'meta'  => [
                'current_page' => $tasks->currentPage(),
                'last_page'    => $tasks->lastPage(),
                'total'        => $tasks->total(),
            ]
        ];

        return ResponsesHelper::returnData($data, 'Tasks retrieved successfully', Response::HTTP_OK);
    }

    public function getTasksOwner(Request $request)
    {
        $userId  = auth()->id();
        $status  = $request->query('status') ?? null;
        $dueFrom = $request->query('due_from') ?? null;
        $dueTo   = $request->query('due_to') ?? null;


        $tasks = $this->taskService->getUserTasks($userId, $status, $dueFrom, $dueTo);
        $data  = [
            'tasks' => TaskResource::collection($tasks),
            'meta'  => [
                'current_page' => $tasks->currentPage(),
                'last_page'    => $tasks->lastPage(),
                'total'        => $tasks->total(),
            ]
        ];
        return ResponsesHelper::returnData($data, 'Tasks retrieved successfully', Response::HTTP_OK);
    }


    public function create(TaskRequest $request)
    {
        $data = $request->validated();
        $task = $this->taskService->create($data);

        return $task
            ? ResponsesHelper::returnData(new TaskResource($task), 'Task created successfully', Response::HTTP_CREATED)
            : ResponsesHelper::returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Task creation failed');
    }

    public function getTaskById($task)
    {
        $userId = auth()->id();
        $task = $this->taskService->getTaskById($task, $userId);

        if (!$task) {
            return ResponsesHelper::returnError(Response::HTTP_NOT_FOUND, 'the task does not exist');
        }
        return ResponsesHelper::returnData(new TaskResource($task), 'Task retrieved successfully', Response::HTTP_OK);
    }

    public function update($task, TaskRequest $request)
    {
        $userId = $request->user_id;
        $data   = $request->validated();
        $task   = $this->taskService->getTaskById($task, $userId);

        if (!$task) {
            return ResponsesHelper::returnError(Response::HTTP_NOT_FOUND, 'the task does not exist');
        }

        $task = $this->taskService->update($task, $data);

        if (!$task) {
            return ResponsesHelper::returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to update task');
        }
        return ResponsesHelper::returnData(new TaskResource($task),  'Task updated successfully', Response::HTTP_OK) ;
    }

    public function delete($task)
    {
        $userId = auth()->id();
        $task = $this->taskService->getTaskById($task, $userId);

        if (!$task) {
            return ResponsesHelper::returnError(Response::HTTP_NOT_FOUND, 'the task does not exist');
        }

        $deleted = $this->taskService->delete($task);

        if (!$deleted) {
            return ResponsesHelper::returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete task');
        }
        return ResponsesHelper::returnSuccessMessage(Response::HTTP_OK, 'Task deleted successfully') ;
    }

    public function complete($taskId)
    {
        try {
            $userId = auth()->id();
            $task   = $this->taskService->getTaskById($taskId, $userId);

            if (!$task) {
                return ResponsesHelper::returnError(Response::HTTP_NOT_FOUND, 'the task does not exist');
            }
            if ($task->status === 'done') {
                return ResponsesHelper::returnError(Response::HTTP_NOT_ACCEPTABLE, 'the task is already done');
            }

            $task = $this->taskService->complete($task);
            event(new TaskCompleted($task));

            return ResponsesHelper::returnData(new TaskResource($task), 'Task done successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            \Log::error('task.complete.failed', ['task_id' => $taskId, 'error' => $e->getMessage()]);
            return ResponsesHelper::returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to complete task');
        }
    }
}
