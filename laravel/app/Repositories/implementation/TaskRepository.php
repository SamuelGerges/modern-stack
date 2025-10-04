<?php

namespace App\Repositories\implementation;

use App\Models\Task;
use App\Repositories\ITaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements ITaskRepository
{
    public function getUserTasks(int $userId, ?string $status = null, ?string $dueFrom = null, ?string $dueTo = null): LengthAwarePaginator
    {
        return Task::query()
            ->select([
                'tasks.id as id',
                'users.name as user_name',
                'tasks.title as title',
                'tasks.description as description',
                'tasks.status as status',
                'tasks.due_date as due_date',
                'tasks.created_at as created_at',
                'tasks.updated_at as updated_at',
            ])
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->where('user_id', $userId)
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dueFrom, function ($query, $dueFrom) {
                return $query->where('due_date','>', $dueFrom);
            })
            ->when($dueTo, function ($query, $dueTo) {
                return $query->where('due_date', '<', $dueTo);
            })
            ->orderByDesc('id')->paginate(10);
    }
    public function create(array $data): Task
    {
        return Task::query()->create([
            'user_id'     => $data['user_id'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'status'      => $data['status'],
            'due_date'    => $data['due_date'],
        ]);
    }

    public function getTaskById(int $id, int $userId): ?Task
    {
        return Task::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();
    }

    public function update(Task $task, array $data): Task
    {
        $task->update([
            'user_id'     => $data['user_id'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'status'      => $data['status'],
            'due_date'    => $data['due_date'],

        ]);
        return $task;
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function complete(Task $task): Task
    {
        $task->update([
            'status' => 'done',
        ]);

        return $task;
    }

}