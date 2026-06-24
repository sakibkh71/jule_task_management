<select class="status-select"
        style="background-color: {{ $task->status_color }}"
        data-task-id="{{ $task->id }}"
        data-update-url="{{ route('tasks.status', $task) }}"
        data-prev-status="{{ $task->status }}">
    @foreach ($taskStatuses as $status)
        <option value="{{ $status->slug }}"
                data-color="{{ $status->color }}"
                data-label="{{ $status->label }}"
                {{ $task->status === $status->slug ? 'selected' : '' }}>
            {{ $status->label }}
        </option>
    @endforeach
</select>
