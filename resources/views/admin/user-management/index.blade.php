@extends('layouts.admin.app')

@section('title', 'User management')
@section('page-heading', 'Users')

@section('content')
    <div class="page-heading">
        <div>
            <h1>User management</h1>
            <p>Manage accounts, roles, and access to the NR Fitness community.</p>
        </div>
        <div class="page-heading-actions">
            <button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-plus-lg"></i> Add user
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-4"><div class="stat-card"><div class="stat-top"><span class="stat-label">All users</span><span class="stat-icon"><i class="bi bi-people"></i></span></div><div class="stat-value">{{ number_format($counts['all']) }}</div><div class="stat-note">Registered accounts</div></div></div>
        <div class="col-sm-4"><div class="stat-card"><div class="stat-top"><span class="stat-label">Active accounts</span><span class="stat-icon"><i class="bi bi-person-check"></i></span></div><div class="stat-value">{{ number_format($counts['active']) }}</div><div class="stat-note">Can access the platform</div></div></div>
        <div class="col-sm-4"><div class="stat-card"><div class="stat-top"><span class="stat-label">Administrators</span><span class="stat-icon"><i class="bi bi-shield-check"></i></span></div><div class="stat-value">{{ number_format($counts['admins']) }}</div><div class="stat-note">Admin role assigned</div></div></div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>All users</h2>
                <p>Update user details or pause account access without deleting their data.</p>
            </div>
            <form method="GET" action="{{ route('admin.user-management.index') }}" class="admin-search" style="width:min(100%, 280px)">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ $search }}" placeholder="Search name or email" aria-label="Search users">
            </form>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr><th>User</th><th>Role</th><th>Status</th><th>Joined</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    <div><span class="entity-name">{{ $user->name }}</span><span class="entity-meta">{{ $user->email }}</span></div>
                                </div>
                            </td>
                            <td><span class="role-badge">{{ $user->formatted_role_name }}</span></td>
                            <td><span class="state-badge {{ $user->status ? '' : 'inactive' }}"><i class="bi {{ $user->status ? 'bi-check-circle' : 'bi-pause-circle' }}"></i>{{ $user->status ? 'Active' : 'Inactive' }}</span></td>
                            <td>{{ $user->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <div class="table-actions justify-content-end">
                                    <a class="icon-action" href="{{ route('admin.user-management.show', $user) }}" title="View user and progress" aria-label="View {{ $user->name }}"><i class="bi bi-eye"></i></a>
                                    <button class="icon-action" type="button" title="Edit user" aria-label="Edit {{ $user->name }}" data-bs-toggle="modal" data-bs-target="#editUser{{ $user->id }}"><i class="bi bi-pencil"></i></button>
                                    @if ($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.user-management.status', $user) }}" onsubmit="return confirm('Are you sure you want to {{ $user->status ? 'deactivate' : 'activate' }} this account?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $user->status ? 0 : 1 }}">
                                            <button class="icon-action" type="submit" title="{{ $user->status ? 'Deactivate' : 'Activate' }} account" aria-label="{{ $user->status ? 'Deactivate' : 'Activate' }} {{ $user->name }}">
                                                <i class="bi {{ $user->status ? 'bi-person-slash' : 'bi-person-check' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty"><i class="bi bi-person-x"></i>No users match your search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="admin-pagination">{{ $users->links('pagination.admin') }}</div>
        @endif
    </section>

    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.user-management.store') }}">
                @csrf
                <div class="modal-header"><div><h2 class="modal-title" id="createUserTitle">Add a user</h2><p class="text-secondary small mb-0 mt-1">Create a new account with a temporary password.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="admin-form-label" for="new-name">Full name</label><input class="form-control admin-form-control" id="new-name" name="name" required maxlength="255"></div>
                    <div class="mb-3"><label class="admin-form-label" for="new-email">Email address</label><input class="form-control admin-form-control" id="new-email" name="email" type="email" required maxlength="255"></div>
                    <div class="mb-3"><label class="admin-form-label" for="new-password">Temporary password</label><input class="form-control admin-form-control" id="new-password" name="password" type="password" minlength="8" required autocomplete="new-password"></div>
                    <div><label class="admin-form-label" for="new-role">Role</label><select class="form-select admin-form-control" id="new-role" name="role" required>@foreach (config('constant.role') as $key => $role)<option value="{{ $key }}">{{ $role }}</option>@endforeach</select></div>
                </div>
                <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Create user</button></div>
            </form>
        </div>
    </div>

    @foreach ($users as $user)
        <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1" aria-labelledby="editUserTitle{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.user-management.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editUserTitle{{ $user->id }}">Edit user</h2><p class="text-secondary small mb-0 mt-1">{{ $user->email }}</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="admin-form-label" for="name{{ $user->id }}">Full name</label><input class="form-control admin-form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}" required maxlength="255"></div>
                        <div class="mb-3"><label class="admin-form-label" for="email{{ $user->id }}">Email address</label><input class="form-control admin-form-control" id="email{{ $user->id }}" name="email" type="email" value="{{ $user->email }}" required maxlength="255"></div>
                        <div><label class="admin-form-label" for="role{{ $user->id }}">Role</label><select class="form-select admin-form-control" id="role{{ $user->id }}" name="role" required @if ($user->id === Auth::id()) disabled @endif>@foreach (config('constant.role') as $key => $role)<option value="{{ $key }}" @selected((string) $user->role === (string) $key)>{{ $role }}</option>@endforeach</select>@if ($user->id === Auth::id())<input type="hidden" name="role" value="{{ $user->role }}"><small class="entity-meta mt-2">Your own administrator role cannot be changed here.</small>@endif</div>
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
