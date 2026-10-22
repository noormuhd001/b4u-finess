@extends('layouts.admin.app')

@section('title', 'Badge management')
@section('page-heading', 'Badges')

@section('content')
    <div class="page-heading">
        <div><h1>Badges & rewards</h1><p>Celebrate user milestones and manage who has earned each badge.</p></div>
        <div class="page-heading-actions"><button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#createBadgeModal"><i class="bi bi-plus-lg"></i> Create badge</button></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6"><div class="stat-card"><div class="stat-top"><span class="stat-label">Badge types</span><span class="stat-icon"><i class="bi bi-award"></i></span></div><div class="stat-value">{{ number_format($badges->total()) }}</div><div class="stat-note">Available rewards</div></div></div>
        <div class="col-sm-6"><div class="stat-card"><div class="stat-top"><span class="stat-label">User accounts</span><span class="stat-icon"><i class="bi bi-people"></i></span></div><div class="stat-value">{{ number_format($users->count()) }}</div><div class="stat-note">Eligible for badge assignments</div></div></div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header"><div><h2>Rewards catalog</h2><p>Edit badge details and manage user assignments. Existing reward history is retained.</p></div></div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Badge</th><th>Description</th><th>Users awarded</th><th class="text-end">Action</th></tr></thead>
                <tbody>
                    @forelse ($badges as $badge)
                        <tr>
                            <td><div class="d-flex align-items-center gap-3">@if ($badge->icon)<img class="badge-image" src="{{ asset($badge->icon) }}" alt="">@else<span class="badge-symbol"><i class="bi bi-award"></i></span>@endif<div><span class="entity-name">{{ $badge->name }}</span><span class="entity-meta">Badge #{{ $badge->id }}</span></div></div></td>
                            <td>{{ $badge->description ?: '—' }}</td>
                            <td><span class="soft-badge"><i class="bi bi-people"></i>{{ $badge->users_count }}</span></td>
                            <td><div class="table-actions justify-content-end"><button class="icon-action" type="button" title="Edit badge" aria-label="Edit {{ $badge->name }}" data-bs-toggle="modal" data-bs-target="#editBadge{{ $badge->id }}"><i class="bi bi-pencil"></i></button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="table-empty"><i class="bi bi-award"></i>No badges yet. Create the first reward for your users.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($badges->hasPages())<div class="admin-pagination">{{ $badges->links('pagination.admin') }}</div>@endif
    </section>

    <div class="modal fade" id="createBadgeModal" tabindex="-1" aria-labelledby="createBadgeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.badges.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><div><h2 class="modal-title" id="createBadgeTitle">Create a badge</h2><p class="text-secondary small mb-0 mt-1">Add a reward and optionally assign it to users.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="admin-form-label" for="badge-name">Badge name</label><input class="form-control admin-form-control" id="badge-name" name="name" required maxlength="255"></div>
                    <div class="mb-3"><label class="admin-form-label" for="badge-icon">Badge icon image (optional)</label><input class="form-control admin-form-control" id="badge-icon" name="icon" type="file" accept="image/*"><small class="entity-meta mt-1">Maximum file size: 5 MB.</small></div>
                    <div class="mb-3"><label class="admin-form-label" for="badge-description">Description</label><textarea class="form-control admin-form-control" id="badge-description" name="description" rows="2" maxlength="1000"></textarea></div>
                    <div><span class="admin-form-label">Assign to users (optional)</span><div class="assignment-list">@forelse ($users as $user)<label><input class="form-check-input" type="checkbox" name="users[]" value="{{ $user->id }}"> {{ $user->name }} <span class="text-secondary">· {{ $user->email }}</span></label>@empty<span class="entity-meta">No user accounts found.</span>@endforelse</div></div>
                </div>
                <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Create badge</button></div>
            </form>
        </div>
    </div>

    @foreach ($badges as $badge)
        <div class="modal fade" id="editBadge{{ $badge->id }}" tabindex="-1" aria-labelledby="editBadgeTitle{{ $badge->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.badges.update', $badge) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editBadgeTitle{{ $badge->id }}">Edit badge</h2><p class="text-secondary small mb-0 mt-1">Update its details and user awards.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="admin-form-label" for="edit-badge-name{{ $badge->id }}">Badge name</label><input class="form-control admin-form-control" id="edit-badge-name{{ $badge->id }}" name="name" value="{{ $badge->name }}" required maxlength="255"></div>
                        <div class="mb-3"><label class="admin-form-label" for="edit-badge-icon{{ $badge->id }}">Replace icon image (optional)</label><input class="form-control admin-form-control" id="edit-badge-icon{{ $badge->id }}" name="icon" type="file" accept="image/*"><small class="entity-meta mt-1">Leave empty to keep the current icon.</small></div>
                        <div class="mb-3"><label class="admin-form-label" for="edit-badge-description{{ $badge->id }}">Description</label><textarea class="form-control admin-form-control" id="edit-badge-description{{ $badge->id }}" name="description" rows="2" maxlength="1000">{{ $badge->description }}</textarea></div>
                        <div><span class="admin-form-label">Awarded to</span><div class="assignment-list">@forelse ($users as $user)<label><input class="form-check-input" type="checkbox" name="users[]" value="{{ $user->id }}" @checked($badge->users->contains('id', $user->id))> {{ $user->name }} <span class="text-secondary">· {{ $user->email }}</span></label>@empty<span class="entity-meta">No user accounts found.</span>@endforelse</div></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
