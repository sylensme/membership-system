@extends('layouts.member')

@section('title', 'Tasks · Member Desk')
@section('context', 'Follow-up work')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Tasks</h1>
            <p>{{ $tasks->count() }} saved {{ Str::plural('task', $tasks->count()) }}. Keep the next piece of member work visible.</p>
        </div>
    </div>

    <div class="task-layout">
        <form class="panel panel-pad" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <h2>Add a task</h2>
            <div class="field">
                <label for="task_title">Task title</label>
                <input type="text" id="task_title" name="task_title" value="{{ old('task_title') }}" required>
                @error('task_title')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Add the useful details">{{ old('description') }}</textarea>
            </div>
            <button class="button" type="submit">Save task</button>
        </form>

        <section aria-labelledby="saved-tasks-title">
            <div class="table-meta"><strong id="saved-tasks-title">Saved tasks</strong><span>{{ $tasks->count() }} total</span></div>
            <div class="task-list">
                @forelse ($tasks as $task)
                    <article class="task-item">
                        <h2>{{ $task->task_title }}</h2>
                        <p>{{ $task->description ?: 'No description provided.' }}</p>
                        <span class="task-item__status">{{ $task->completed ? 'Completed' : 'Open' }}</span>
                    </article>
                @empty
                    <div class="panel empty-state">
                        <div>
                            <div class="empty-state__mark" aria-hidden="true"></div>
                            <h2>No saved tasks</h2>
                            <p>Add the first follow-up and it will stay here beside the member directory.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
