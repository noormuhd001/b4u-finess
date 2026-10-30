@extends('layouts.admin.app')

@section('title', 'Badge management')
@section('page-heading', 'Badges')

@section('content')
    <div class="page-heading">
        <div><h1>Badges</h1><p>Edit badge names and logos.</p></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4"><div class="stat-card"><div class="stat-top"><span class="stat-label">Badge types</span><span class="stat-icon"><i class="bi bi-award"></i></span></div><div class="stat-value">{{ number_format($badges->total()) }}</div><div class="stat-note">Available rewards</div></div></div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header"><div><h2>Badge catalog</h2><p>Update a badge's name or logo. Existing awards are retained.</p></div></div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead><tr><th>Badge</th><th class="text-end">Action</th></tr></thead>
                <tbody>
                    @forelse ($badges as $badge)
                        <tr>
                            <td><div class="d-flex align-items-center gap-3">@if ($badge->icon)<img class="badge-image" src="{{ asset($badge->icon) }}" alt="">@else<span class="badge-symbol"><i class="bi bi-award"></i></span>@endif<div><span class="entity-name">{{ $badge->name }}</span><span class="entity-meta">Badge #{{ $badge->id }}</span></div></div></td>
                            <td><div class="table-actions justify-content-end"><button class="icon-action" type="button" title="Edit badge" aria-label="Edit {{ $badge->name }}" data-bs-toggle="modal" data-bs-target="#editBadge{{ $badge->id }}"><i class="bi bi-pencil"></i></button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="table-empty"><i class="bi bi-award"></i>No badges found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($badges->hasPages())<div class="admin-pagination">{{ $badges->links('pagination.admin') }}</div>@endif
    </section>

    @foreach ($badges as $badge)
        <div class="modal fade" id="editBadge{{ $badge->id }}" tabindex="-1" aria-labelledby="editBadgeTitle{{ $badge->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.badges.update', $badge) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editBadgeTitle{{ $badge->id }}">Edit badge</h2><p class="text-secondary small mb-0 mt-1">Update the badge name or logo.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="admin-form-label" for="edit-badge-name{{ $badge->id }}">Badge name</label><input class="form-control admin-form-control" id="edit-badge-name{{ $badge->id }}" name="name" value="{{ $badge->name }}" required maxlength="255"></div>
                        <div><label class="admin-form-label" for="edit-badge-icon{{ $badge->id }}">Replace logo (optional)</label><input class="form-control admin-form-control" id="edit-badge-icon{{ $badge->id }}" name="icon" type="file" accept="image/*"><small class="entity-meta mt-1">Leave empty to keep the current logo. Maximum file size: 5 MB.</small></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
