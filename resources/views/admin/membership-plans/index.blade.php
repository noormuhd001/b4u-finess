@extends('layouts.admin.app')

@section('title', 'Membership plans')
@section('page-heading', 'Membership plans')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Membership plans</h1>
            <p>Create and update the plans available to members during registration.</p>
        </div>
        <div class="page-heading-actions">
            <button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                <i class="bi bi-plus-lg"></i> Add plan
            </button>
        </div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Plan catalog</h2>
                <p>Inactive plans stay linked to existing members but are hidden from new registrations.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr><th>Plan</th><th>Price</th><th>Duration</th><th>Members</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        <tr>
                            <td>
                                <span class="entity-name">{{ $plan->name }}</span>
                                <span class="entity-meta">{{ $plan->description ?: 'No description' }}</span>
                            </td>
                            <td>₹{{ number_format((float) $plan->price, 2) }}</td>
                            <td>{{ $plan->duration_months }} {{ \Illuminate\Support\Str::plural('month', $plan->duration_months) }}</td>
                            <td><span class="soft-badge"><i class="bi bi-people"></i>{{ $plan->users_count }}</span></td>
                            <td><span class="state-badge {{ $plan->is_active ? '' : 'inactive' }}">{{ $plan->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="table-actions justify-content-end">
                                    <button class="icon-action" type="button" title="Edit plan" aria-label="Edit {{ $plan->name }}" data-bs-toggle="modal" data-bs-target="#editPlan{{ $plan->id }}"><i class="bi bi-pencil"></i></button>
                                    <form method="POST" action="{{ route('admin.membership-plans.status', $plan) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $plan->is_active ? 0 : 1 }}">
                                        <button class="icon-action" type="submit" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }} plan" aria-label="{{ $plan->is_active ? 'Deactivate' : 'Activate' }} {{ $plan->name }}">
                                            <i class="bi {{ $plan->is_active ? 'bi-pause-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="table-empty"><i class="bi bi-card-checklist"></i>No membership plans yet. Add a plan or seed the default plans.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.membership-plans.store') }}">
                @csrf
                <div class="modal-header"><div><h2 class="modal-title" id="createPlanTitle">Add membership plan</h2><p class="text-secondary small mb-0 mt-1">New plans will be available for registration.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="admin-form-label" for="new-plan-name">Plan name</label><input class="form-control admin-form-control" id="new-plan-name" name="name" required maxlength="255"></div>
                    <div class="mb-3"><label class="admin-form-label" for="new-plan-description">Description</label><textarea class="form-control admin-form-control" id="new-plan-description" name="description" rows="2" maxlength="1000"></textarea></div>
                    <div class="row g-3">
                        <div class="col-sm-6"><label class="admin-form-label" for="new-plan-price">Price (₹)</label><input class="form-control admin-form-control" id="new-plan-price" name="price" type="number" min="0" step="0.01" required></div>
                        <div class="col-sm-6"><label class="admin-form-label" for="new-plan-duration">Duration (months)</label><input class="form-control admin-form-control" id="new-plan-duration" name="duration_months" type="number" min="1" max="120" value="1" required></div>
                    </div>
                    <input type="hidden" name="is_active" value="1">
                </div>
                <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Create plan</button></div>
            </form>
        </div>
    </div>

    @foreach ($plans as $plan)
        <div class="modal fade" id="editPlan{{ $plan->id }}" tabindex="-1" aria-labelledby="editPlanTitle{{ $plan->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.membership-plans.update', $plan) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editPlanTitle{{ $plan->id }}">Edit membership plan</h2><p class="text-secondary small mb-0 mt-1">{{ $plan->users_count }} current members</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="admin-form-label" for="plan-name{{ $plan->id }}">Plan name</label><input class="form-control admin-form-control" id="plan-name{{ $plan->id }}" name="name" value="{{ $plan->name }}" required maxlength="255"></div>
                        <div class="mb-3"><label class="admin-form-label" for="plan-description{{ $plan->id }}">Description</label><textarea class="form-control admin-form-control" id="plan-description{{ $plan->id }}" name="description" rows="2" maxlength="1000">{{ $plan->description }}</textarea></div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6"><label class="admin-form-label" for="plan-price{{ $plan->id }}">Price (₹)</label><input class="form-control admin-form-control" id="plan-price{{ $plan->id }}" name="price" type="number" min="0" step="0.01" value="{{ $plan->price }}" required></div>
                            <div class="col-sm-6"><label class="admin-form-label" for="plan-duration{{ $plan->id }}">Duration (months)</label><input class="form-control admin-form-control" id="plan-duration{{ $plan->id }}" name="duration_months" type="number" min="1" max="120" value="{{ $plan->duration_months }}" required></div>
                        </div>
                        <div><label class="admin-form-label" for="plan-active{{ $plan->id }}">Availability</label><select class="form-select admin-form-control" id="plan-active{{ $plan->id }}" name="is_active" required><option value="1" @selected($plan->is_active)>Active</option><option value="0" @selected(!$plan->is_active)>Inactive</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
