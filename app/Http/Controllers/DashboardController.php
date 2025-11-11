<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $taskStats = Task::query()
            ->select(
                DB::raw('count(*) as total_tasks'),
                DB::raw("count(case when status = 'Pending' then 1 end) as pending_tasks"),
                DB::raw("count(case when status = 'In Progress' then 1 end) as in_progress_tasks"),
                DB::raw("count(case when status = 'Completed' then 1 end) as completed_tasks")
            )
            ->first();

        $recentTasks = Task::with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $completedTasksChart = Task::where('status', 'Completed')
            ->select(DB::raw('WEEK(created_at) as week'), DB::raw('count(*) as count'))
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->pluck('count', 'week')
            ->all();

        $addedTasksChart = Task::select(DB::raw('WEEK(created_at) as week'), DB::raw('count(*) as count'))
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->pluck('count', 'week')
            ->all();

        return view('home', [
            'totalTasks' => $taskStats->total_tasks ?? 0,
            'pendingTasks' => $taskStats->pending_tasks ?? 0,
            'inProgressTasks' => $taskStats->in_progress_tasks ?? 0,
            'completedTasks' => $taskStats->completed_tasks ?? 0,
            'recentActivities' => $recentTasks,
            'chartCompleted' => $completedTasksChart,
            'chartAdded' => $addedTasksChart,
        ]);
    }
}
