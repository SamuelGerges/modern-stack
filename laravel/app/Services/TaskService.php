<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\ITaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    protected $taskRepository;
    public function __construct(ITaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    public function getUserTasks(int $userId, ?string $status = null, ?string $dueFrom = null, ?string $dueTo = null): LengthAwarePaginator
    {
        return $this->taskRepository->getUserTasks($userId, $status, $dueFrom, $dueTo);
    }
    public function getTaskById(int $id, int $userId): ?Task
    {
        return $this->taskRepository->getTaskById($id, $userId);
    }
    public function create($data): Task
    {
        return $this->taskRepository->create($data);
    }
    public function update($task, $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    public function delete(Task $task): bool
    {
        return $this->taskRepository->delete($task);
    }

    public function complete($task): Task
    {
        return $this->taskRepository->complete($task);
    }

}