<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITaskRepository
{
    public function getUserTasks(int $userId, ?string $status = null, ?string $dueFrom = null, ?string $dueTo = null): LengthAwarePaginator;
    public function getTaskById(int $id,int $userId): ?Task;
    public function create(array $data): Task;
    public function update(Task $task, array $data): Task;
    public function delete(Task $task): bool;
    public function complete(Task $task): Task;

}