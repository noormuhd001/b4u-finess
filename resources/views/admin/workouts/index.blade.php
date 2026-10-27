@extends('layouts.admin.app')

@section('title', 'Workout library')
@section('page-heading', 'Workouts')

@section('content')
    <div class="page-heading">
        <div><h1>Workout library</h1><p>Build and maintain the exercise catalog available to every user.</p></div>
        <div class="page-heading-actions"><button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#createWorkoutModal"><i class="bi bi-plus-lg"></i> Add workout</button></div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header">
            <div><h2>All workouts</h2><p>Update workout details and images. Existing workout history is preserved.</p></div>
            <span class="soft-badge">{{ $workouts->total() }} workouts</span>
        </div>
        <form class="workout-filter-form d-flex flex-wrap align-items-end gap-2 mb-3" method="GET" action="{{ route('admin.workouts.index') }}">
            <div>
                <label class="admin-form-label" for="filter-body-part">Filter by body part</label>
                <select class="form-select admin-form-control" id="filter-body-part" name="body_part">
                    <option value="">All body parts</option>
                    @foreach ($bodyParts as $bodyPartOption)
                        <option value="{{ $bodyPartOption }}" @selected($selectedBodyPart === $bodyPartOption)>{{ $bodyPartOption }}</option>
                    @endforeach
                </select>
            </div>
            <div class="workout-filter-actions">
                <button class="admin-btn" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                @if ($selectedBodyPart !== '')
                    <a class="admin-btn admin-btn-light" href="{{ route('admin.workouts.index') }}">Clear</a>
                @endif
            </div>
        </form>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Workout</th><th>Body part</th><th>Calories / min</th><th class="text-end">Action</th></tr></thead>
                <tbody>
                    @forelse ($workouts as $workout)
                        <tr>
                            <td><div class="d-flex align-items-center gap-3"><img class="workout-thumb" src="{{ $workout->image_url }}" alt=""><div><span class="entity-name">{{ $workout->workout_name }}</span><span class="entity-meta">Workout #{{ $workout->id }}</span></div></div></td>
                            <td><span class="soft-badge">{{ $workout->body_part }}</span></td>
                            <td>{{ $workout->kcal_per_minute }} kcal</td>
                            <td><div class="table-actions justify-content-end"><button class="icon-action" type="button" title="Edit workout" aria-label="Edit {{ $workout->workout_name }}" data-bs-toggle="modal" data-bs-target="#editWorkout{{ $workout->id }}"><i class="bi bi-pencil"></i></button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="table-empty"><i class="bi bi-activity"></i>Your workout library is empty.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($workouts->hasPages())<div class="admin-pagination">{{ $workouts->links('pagination.admin') }}</div>@endif
    </section>

    <div class="modal fade" id="createWorkoutModal" tabindex="-1" aria-labelledby="createWorkoutTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.workouts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><div><h2 class="modal-title" id="createWorkoutTitle">Add workout</h2><p class="text-secondary small mb-0 mt-1">Create a new exercise in the shared library.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="admin-form-label" for="workout-name">Workout name</label><input class="form-control admin-form-control" id="workout-name" name="workout_name" required maxlength="255"></div>
                    <div class="mb-3"><label class="admin-form-label" for="workout-part">Body part</label><select class="form-select admin-form-control" id="workout-part" name="body_part" required><option value="" disabled selected>Select a body part</option>@foreach ($bodyParts as $bodyPartOption)<option value="{{ $bodyPartOption }}" @selected(old('body_part') === $bodyPartOption)>{{ $bodyPartOption }}</option>@endforeach</select></div>
                    <div class="mb-3"><label class="admin-form-label" for="workout-kcal">Calories per minute</label><input class="form-control admin-form-control" id="workout-kcal" name="kcal_per_minute" type="number" step="0.1" min="0" required></div>
                    <div><label class="admin-form-label" for="workout-image">Workout image</label><input class="form-control admin-form-control" id="workout-image" name="image" type="file" accept="image/*" required><small class="entity-meta mt-1">Maximum file size: 5 MB.</small></div>
                </div>
                <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Add workout</button></div>
            </form>
        </div>
    </div>

    @foreach ($workouts as $workout)
        <div class="modal fade" id="editWorkout{{ $workout->id }}" tabindex="-1" aria-labelledby="editWorkoutTitle{{ $workout->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.workouts.update', $workout) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editWorkoutTitle{{ $workout->id }}">Edit workout</h2><p class="text-secondary small mb-0 mt-1">Workout #{{ $workout->id }}</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="admin-form-label" for="edit-workout-name{{ $workout->id }}">Workout name</label><input class="form-control admin-form-control" id="edit-workout-name{{ $workout->id }}" name="workout_name" value="{{ $workout->workout_name }}" required maxlength="255"></div>
                        <div class="mb-3"><label class="admin-form-label" for="edit-workout-part{{ $workout->id }}">Body part</label><select class="form-select admin-form-control" id="edit-workout-part{{ $workout->id }}" name="body_part" required>@foreach ($bodyParts as $bodyPartOption)<option value="{{ $bodyPartOption }}" @selected($workout->body_part === $bodyPartOption)>{{ $bodyPartOption }}</option>@endforeach</select></div>
                        <div class="mb-3"><label class="admin-form-label" for="edit-workout-kcal{{ $workout->id }}">Calories per minute</label><input class="form-control admin-form-control" id="edit-workout-kcal{{ $workout->id }}" name="kcal_per_minute" type="number" step="0.1" min="0" value="{{ $workout->kcal_per_minute }}" required></div>
                        <div><label class="admin-form-label" for="edit-workout-image{{ $workout->id }}">Replace image (optional)</label><input class="form-control admin-form-control" id="edit-workout-image{{ $workout->id }}" name="image" type="file" accept="image/*"><small class="entity-meta mt-2">Leave empty to keep the current image.</small></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
