@extends('layouts.trainer.app')

@section('title', $user->name)

@section('content')
    <div class="trainer-back-link">
        <a href="{{ route('trainer.dashboard') }}"><i class="bi bi-arrow-left"></i> Back to my members</a>
    </div>
    <section class="trainer-member-profile">
        <span class="trainer-member-avatar trainer-member-avatar-large">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        <div class="trainer-profile-main">
            <span class="trainer-eyebrow">MEMBER PROFILE</span>
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }}</p>
        </div>
        <div class="trainer-profile-tags">
            <span><i class="bi bi-card-checklist"></i>{{ $user->membershipPlan?->name ?? 'No membership plan' }}</span>
            @if ($user->goal)<span><i class="bi bi-bullseye"></i>{{ config('constant.goal.'.$user->goal, 'Fitness goal set') }}</span>@endif
            @if ($user->weight)<span><i class="bi bi-speedometer2"></i>{{ $user->weight }} kg</span>@endif
        </div>
    </section>

    <section class="trainer-panel">
        <div class="trainer-panel-heading">
            <div>
                <h2>{{ $editingPlan ? 'Update daily coaching plan' : 'Create daily coaching plan' }}</h2>
                <p>Write clear, actionable guidance the member can follow today.</p>
            </div>
        </div>
        <form class="trainer-coaching-form" method="POST" action="{{ route('trainer.members.daily-plan', $user) }}">
            @csrf
            <div class="trainer-form-date">
                <label class="form-label" for="plan-date">Plan date</label>
                <input class="form-control" id="plan-date" name="plan_date" type="date" value="{{ old('plan_date', $editingPlan?->plan_date?->format('Y-m-d') ?? today()->toDateString()) }}" required>
            </div>
            <div>
                <label class="form-label" for="daily-instruction">Daily training instructions</label>
                <textarea class="form-control" id="daily-instruction" name="daily_instruction" rows="4" maxlength="5000" placeholder="Exercises, sets, reps, warm-up, recovery..." required>{{ old('daily_instruction', $editingPlan?->daily_instruction) }}</textarea>
            </div>
            <div>
                <label class="form-label" for="diet-plan">Diet plan</label>
                <textarea class="form-control" id="diet-plan" name="diet_plan" rows="4" maxlength="10000" placeholder="Meals, portions, hydration, and nutrition guidance...">{{ old('diet_plan', $editingPlan?->diet_plan) }}</textarea>
            </div>
            <div>
                <label class="form-label" for="progress-feedback">Progress feedback</label>
                <textarea class="form-control" id="progress-feedback" name="progress_feedback" rows="3" maxlength="5000" placeholder="Encouragement or next steps based on the member's recent progress...">{{ old('progress_feedback', $editingPlan?->progress_feedback) }}</textarea>
            </div>
            <div class="trainer-form-actions">
                <button class="btn trainer-primary-btn" type="submit"><i class="bi bi-send"></i> {{ $editingPlan ? 'Update plan' : 'Send plan to member' }}</button>
                @if ($editingPlan)<a class="btn trainer-outline-btn" href="{{ route('trainer.members.show', $user) }}">Cancel edit</a>@endif
            </div>
        </form>
    </section>

    <section class="trainer-panel">
        <div class="trainer-panel-heading">
            <div><h2>Recent daily plans</h2><p>Plans and feedback you’ve shared with {{ $user->name }}.</p></div>
        </div>
        <div class="trainer-plan-list">
            @forelse ($dailyPlans as $plan)
                <article class="trainer-plan-item">
                    <div class="trainer-plan-date">
                        <strong>{{ $plan->plan_date->format('M j') }}</strong>
                        <span>{{ $plan->plan_date->format('Y') }}</span>
                    </div>
                    <div class="trainer-plan-copy">
                        <h3>Daily training instructions</h3>
                        <p>{{ $plan->daily_instruction }}</p>
                        @if ($plan->diet_plan)<h4>Diet plan</h4><p>{{ $plan->diet_plan }}</p>@endif
                        @if ($plan->progress_feedback)<h4>Progress feedback</h4><p>{{ $plan->progress_feedback }}</p>@endif
                    </div>
                    <a class="btn trainer-outline-btn" href="{{ route('trainer.members.show', ['user' => $user, 'edit_plan' => $plan->plan_date->format('Y-m-d')]) }}">Edit</a>
                </article>
            @empty
                <div class="trainer-empty-state trainer-empty-compact">
                    <span><i class="bi bi-journal-text"></i></span>
                    <h3>No daily plans yet</h3>
                    <p>Create a plan above to share instructions and nutrition guidance with this member.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="trainer-panel">
        <div class="trainer-panel-heading">
            <div><h2>Member progress</h2><p>Use recent check-ins to personalize training and recovery advice.</p></div>
        </div>
        <div class="table-responsive">
            <table class="table trainer-progress-table align-middle mb-0">
                <thead><tr><th>Date</th><th>Weight</th><th>Calories</th><th>Exercises logged</th></tr></thead>
                <tbody>
                    @forelse ($progress as $entry)
                        <tr>
                            <td>{{ $entry->workout_on?->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $entry->current_weight }} kg</td>
                            <td>{{ number_format((float) $entry->avg_kcal_burned) }} kcal</td>
                            <td>
                                <span>{{ $entry->logs->count() }} {{ \Illuminate\Support\Str::plural('exercise', $entry->logs->count()) }}</span>
                                @if ($entry->logs->isNotEmpty())
                                    <details class="trainer-exercise-details">
                                        <summary>View sets and reps</summary>
                                        <ul>
                                            @foreach ($entry->logs as $log)
                                                <li>{{ $log->workout?->workout_name ?? 'Workout' }} · {{ $log->set_number }} × {{ $log->reps }} · {{ $log->weight }} kg</li>
                                            @endforeach
                                        </ul>
                                    </details>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center py-4" colspan="4">This member hasn’t recorded progress yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($progress->hasPages())<div class="trainer-pagination">{{ $progress->links() }}</div>@endif
    </section>
@endsection
