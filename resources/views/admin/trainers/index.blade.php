@extends('layouts.admin.app')

@section('title', 'Trainer management')
@section('page-heading', 'Trainers')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Trainer management</h1>
            <p>Create trainer login accounts and keep their contact and coaching information up to date.</p>
        </div>
        <div class="page-heading-actions">
            <button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#createTrainerModal">
                <i class="bi bi-plus-lg"></i> Add trainer
            </button>
        </div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header">
            <div><h2>Trainer directory</h2><p>Removing a trainer clears their assignments from member accounts.</p></div>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Trainer</th><th>Contact</th><th>Specialty</th><th>Experience</th><th>Members</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse ($trainers as $trainer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($trainer->photo)
                                        <img src="{{ asset('storage/'.$trainer->photo) }}" alt="" class="rounded-circle" style="width:44px;height:44px;object-fit:cover">
                                    @else
                                        <span class="avatar-initial">{{ strtoupper(substr($trainer->name, 0, 1)) }}</span>
                                    @endif
                                    <div><span class="entity-name">{{ $trainer->name }}</span><span class="entity-meta">{{ $trainer->bio ?: 'No bio added' }}</span></div>
                                </div>
                            </td>
                            <td><span class="entity-name">{{ $trainer->email ?: '—' }}</span><span class="entity-meta">{{ $trainer->phone ?: '—' }}</span></td>
                            <td>{{ $trainer->specialty }}</td>
                            <td>{{ $trainer->experience_years }} {{ \Illuminate\Support\Str::plural('year', $trainer->experience_years) }}</td>
                            <td><span class="soft-badge"><i class="bi bi-people"></i>{{ $trainer->users_count }}</span></td>
                            <td><span class="state-badge {{ $trainer->is_active ? '' : 'inactive' }}">{{ $trainer->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="table-actions justify-content-end">
                                    <button class="icon-action" type="button" title="Edit trainer" aria-label="Edit {{ $trainer->name }}" data-bs-toggle="modal" data-bs-target="#editTrainer{{ $trainer->id }}"><i class="bi bi-pencil"></i></button>
                                    <form method="POST" action="{{ route('admin.trainers.destroy', $trainer) }}" data-confirm-title="Remove trainer" data-confirm-message="Remove {{ $trainer->name }}? Their member assignments will also be cleared." data-confirm-label="Remove trainer" data-confirm-variant="danger">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-action text-danger" type="submit" title="Remove trainer" aria-label="Remove {{ $trainer->name }}"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="table-empty"><i class="bi bi-person-badge"></i>No trainers yet. Add a trainer to get started.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($trainers->hasPages())<div class="admin-pagination">{{ $trainers->links('pagination.admin') }}</div>@endif
    </section>

    <div class="modal fade" id="createTrainerModal" tabindex="-1" aria-labelledby="createTrainerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" method="POST" action="{{ route('admin.trainers.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><div><h2 class="modal-title" id="createTrainerTitle">Add trainer</h2><p class="text-secondary small mb-0 mt-1">Create login credentials and add the trainer’s coaching information.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">@include('admin.trainers.fields', ['trainer' => null, 'prefix' => 'new'])</div>
                <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Add trainer</button></div>
            </form>
        </div>
    </div>

    @foreach ($trainers as $trainer)
        <div class="modal fade" id="editTrainer{{ $trainer->id }}" tabindex="-1" aria-labelledby="editTrainerTitle{{ $trainer->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form class="modal-content" method="POST" action="{{ route('admin.trainers.update', $trainer) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editTrainerTitle{{ $trainer->id }}">Edit trainer</h2><p class="text-secondary small mb-0 mt-1">{{ $trainer->users_count }} assigned members</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">@include('admin.trainers.fields', ['trainer' => $trainer, 'prefix' => 'edit'.$trainer->id])</div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
