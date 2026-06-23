<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalTasks     = Task::count();
        $totalUsers     = User::count();
        $completedTasks = Task::where('status', 'completed')->count();

        $recentTasks = Task::with(['technician', 'client', 'creator'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('totalTasks', 'totalUsers', 'completedTasks', 'recentTasks'));
    }
}
