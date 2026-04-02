@extends('layouts.admin.app')

@section('content')

@push('styles')
<style>
    .table th,
    .table td {
        vertical-align: middle;
    }

    .card-wrapper {
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 20px;
        background: #fff;
    }
</style>
@endpush

<div class="container mt-4">
    <h2 class="mb-3">User Management</h2>

    <div class="card-wrapper">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th width="120">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>

                        <td>
                            <strong>{{ $user->name }}</strong><br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $user->formatted_role_name }}
                            </span>
                        </td>

                        <td>{{ $user->created_at->format('d M Y') }}</td>

                        <td>
                            <button
                                class="btn btn-sm btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editRoleModal"
                                data-user-id="{{ $user->id }}"
                                data-user-role="{{ $user->role }}"
                            >
                                Edit Role
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL ================= --}}
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.user-management.updateRole') }}">
            @csrf

            <input type="hidden" name="user_id" id="modal_user_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Select Role</label>
                    <select name="role" id="modal_user_role" class="form-select" required>
                        @foreach (config('constant.role') as $key => $role)
                            <option value="{{ $key }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editRoleModal = document.getElementById('editRoleModal');

        editRoleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const userId = button.getAttribute('data-user-id');
            const userRole = button.getAttribute('data-user-role');

            document.getElementById('modal_user_id').value = userId;
            document.getElementById('modal_user_role').value = userRole;
        });
    });
</script>
@endpush
