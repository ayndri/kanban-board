<?php

namespace App\Http\Controllers;

use App\Notifications\NewTaskAssigned;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $statusDefinitions = [
            'to-do' => 'To Do',
            'in-progress' => 'In Progress',
            'in-review' => 'In Review',
            'completed' => 'Completed'
        ];

        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->photo_url ?? 'https://placehold.co/32x32/E91E63/FFFFFF?text=' . $user->name[0]
            ];
        });

        $tasks = Task::with('user')->orderBy('order', 'asc')->get();

        $statuses = [];
        foreach ($statusDefinitions as $slug => $title) {
            $statuses[] = [
                'id' => $slug,
                'title' => $title,
                'slug' => $slug,
                'tasks' => $tasks->where('status', $slug)->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'due_date' => $task->due_date,
                        'user_id' => $task->user_id,
                        'status' => $task->status
                    ];
                })->values()->all()
            ];
        }

        return view('tasks.task', [
            'statusesJson' => $statuses,
            'usersJson' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'priority' => 'required|string',
            'due_date' => 'nullable|date',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'order' => Task::where('status', $request->status)->max('order') + 1,
            'start_date' => $request->status == 'in-progress' ? Carbon::now() : null
        ]);

        $userToNotify = User::find($task->user_id);
        if ($userToNotify) {
            $userToNotify->notify(new NewTaskAssigned($task));
        }

        $taskData = $task->load('user');

        return response()->json($taskData, 201);
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id'     => 'nullable|exists:users,id',
            'priority'    => 'required|string',
            'due_date'    => 'nullable|date',
            'status'      => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $task->update($request->all());
        return response()->json($task->load('user'), 200);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'taskId' => 'required|integer|exists:tasks,id',
            'newStatus' => 'required|string',
            'newOrder' => 'required|integer',
            'orderData' => 'nullable|array'
        ]);

        $task = Task::find($request->taskId);

        $dataToUpdate = [
            'status' => $request->newStatus,
            'order' => $request->newOrder,
        ];

        if ($request->newStatus == 'in-progress' && is_null($task->start_date)) {
            $dataToUpdate['start_date'] = Carbon::now();
        }

        $task->update($dataToUpdate);

        if ($request->has('orderData')) {
            foreach ($request->orderData as $item) {
                Task::where('id', $item['id'])->update(['order' => $item['order']]);
            }
        }

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task->refresh()
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
