<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['technician', 'client', 'creator'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($inner) use ($search) {
                    $inner->where('job_number', 'like', "%{$search}%")
                          ->orWhere('title', 'like', "%{$search}%")
                          ->orWhereHas('technician', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                          ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('job_type'), fn ($q) => $q->where('job_type', $request->job_type))
            ->when($request->filled('job_status'), fn ($q) => $q->where('status', $request->job_status))
            ->when($request->filled('technician_id'), fn ($q) => $q->where('technician_id', $request->technician_id));

        $tasks       = $query->latest()->paginate(10)->withQueryString();
        $technicians = User::orderBy('name')->get();
        $clients     = Client::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'technicians', 'clients'));
    }

    public function create()
    {
        $technicians = User::orderBy('name')->get();
        $clients     = Client::orderBy('name')->get();
        return view('tasks.create', compact('technicians', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:assigned,in_progress,confirmed,completed',
            'job_type'      => 'required|in:project,service_work,on_call,assignment',
            'due_date'      => 'nullable|date',
            'start_time'    => 'nullable|date_format:Y-m-d\TH:i',
            'end_time'      => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_time',
            'technician_id' => 'nullable|exists:users,id',
            'client_id'     => 'nullable|exists:clients,id',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['user_id']    = Auth::id();

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Job created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('view', $task);
        $technicians = User::orderBy('name')->get();
        $clients     = Client::orderBy('name')->get();
        return view('tasks.edit', compact('task', 'technicians', 'clients'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:assigned,in_progress,confirmed,completed',
            'job_type'      => 'required|in:project,service_work,on_call,assignment',
            'due_date'      => 'nullable|date',
            'start_time'    => 'nullable|date_format:Y-m-d\TH:i',
            'end_time'      => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_time',
            'technician_id' => 'nullable|exists:users,id',
            'client_id'     => 'nullable|exists:clients,id',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Job deleted successfully.');
    }
}
