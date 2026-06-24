<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['technician', 'client', 'creator', 'taskStatus'])
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

        $tasks        = $query->latest()->paginate(10)->withQueryString();
        $technicians  = User::orderBy('name')->get();
        $clients      = Client::orderBy('name')->get();
        $taskStatuses = TaskStatus::activeList();

        return view('tasks.index', compact('tasks', 'technicians', 'clients', 'taskStatuses'));
    }

    public function create(Request $request)
    {
        $technicians  = User::orderBy('name')->get();
        $clients      = Client::orderBy('name')->get();
        $taskStatuses = TaskStatus::activeList();
        $backUrl      = $this->resolveRedirectUrl($request->query('back'));

        return view('tasks.create', compact('technicians', 'clients', 'taskStatuses', 'backUrl'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => $this->statusRules(),
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

        return redirect($this->resolveRedirectUrl($request->input('redirect')))
            ->with('success', 'Job created successfully.');
    }

    public function edit(Request $request, Task $task)
    {
        // $this->authorize('view', $task);
        $technicians  = User::orderBy('name')->get();
        $clients      = Client::orderBy('name')->get();
        $taskStatuses = TaskStatus::activeList();
        $backUrl      = $this->resolveRedirectUrl($request->query('back'));

        return view('tasks.create', compact('task', 'technicians', 'clients', 'taskStatuses', 'backUrl'));
    }

    public function update(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => $this->statusRules(),
            'job_type'      => 'required|in:project,service_work,on_call,assignment',
            'due_date'      => 'nullable|date',
            'start_time'    => 'nullable|date_format:Y-m-d\TH:i',
            'end_time'      => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_time',
            'technician_id' => 'nullable|exists:users,id',
            'client_id'     => 'nullable|exists:clients,id',
        ]);

        $task->update($validated);

        return redirect($this->resolveRedirectUrl($request->input('redirect')))
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Task $task)
    {
        // $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Job deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => $this->statusRules(),
        ]);

        $task->update(['status' => $validated['status']]);
        $task->load('taskStatus');

        return response()->json([
            'message'      => 'Status updated successfully.',
            'status'       => $task->status,
            'status_label' => $task->status_label,
            'status_color' => $task->status_color,
        ]);
    }

    public function assignTechnician(Request $request, Task $task)
    {
        // $this->authorize('update', $task);

        $validated = $request->validate([
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $task->update(['technician_id' => $validated['technician_id']]);

        $task->load('technician');

        return response()->json([
            'message'         => 'Technician assigned successfully.',
            'technician_id'   => $task->technician_id,
            'technician_name' => $task->technician?->name,
        ]);
    }

    private function resolveRedirectUrl(?string $url): string
    {
        $default = route('tasks.index');

        if (!$url) {
            return $default;
        }

        $appUrl = rtrim(url('/'), '/');

        if ($url === $appUrl || str_starts_with($url, $appUrl . '/')) {
            return $url;
        }

        return $default;
    }

    private function statusRules(): array
    {
        return [
            'required',
            Rule::exists('task_statuses', 'slug')->where(fn ($query) => $query->where('is_active', true)),
        ];
    }
}
