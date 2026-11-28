@extends('layouts.trainer.app')

@section('title', 'My members')

@section('content')
    <section class="trainer-page-heading">
        <div>
            <span class="trainer-eyebrow">YOUR COACHING SPACE</span>
            <h1>Welcome, {{ $trainer->name }}</h1>
            <p>Review member progress and keep their daily training and nutrition guidance up to date.</p>
        </div>
        <div class="trainer-member-count"><i class="bi bi-people"></i><span><strong>{{ number_format($members->total()) }}</strong> assigned {{ \Illuminate\Support\Str::plural('member', $members->total()) }}</span></div>
    </section>

    <section class="trainer-panel">
        <div class="trainer-panel-heading">
            <div><h2>Assigned members</h2><p>Only members assigned to your trainer account are shown here.</p></div>
        </div>
        <div class="trainer-member-list">
            @forelse ($members as $member)
                <article class="trainer-member-row">
                    <span class="trainer-member-avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    <div class="trainer-member-main">
                        <h3>{{ $member->name }}</h3>
                        <span>{{ $member->email }}</span>
                    </div>
                    <div class="trainer-member-meta">
                        <span>{{ $member->membershipPlan?->name ?? 'No plan' }}</span>
                        <small>{{ number_format($member->progress_count) }} progress {{ \Illuminate\Support\Str::plural('entry', $member->progress_count) }}</small>
                    </div>
                    <a class="btn trainer-outline-btn" href="{{ route('trainer.members.show', $member) }}">Review member <i class="bi bi-arrow-right"></i></a>
                </article>
            @empty
                <div class="trainer-empty-state">
                    <span><i class="bi bi-person-plus"></i></span>
                    <h3>No members assigned yet</h3>
                    <p>When an administrator assigns a member to you, they’ll appear here.</p>
                </div>
            @endforelse
        </div>
        @if ($members->hasPages())
            <div class="trainer-pagination">{{ $members->links() }}</div>
        @endif
    </section>

    <section class="trainer-panel trainer-password-panel">
        <div class="trainer-panel-heading">
            <div><h2>Account security</h2><p>Change your password to keep your trainer account secure.</p></div>
        </div>
        <form class="trainer-password-form" method="POST" action="{{ route('trainer.password.update') }}">
            @csrf
            @method('PATCH')
            <div>
                <label class="form-label" for="current-password">Current password</label>
                <input class="form-control" id="current-password" name="current_password" type="password" autocomplete="current-password" required>
            </div>
            <div>
                <label class="form-label" for="new-password">New password</label>
                <input class="form-control" id="new-password" name="password" type="password" minlength="8" autocomplete="new-password" required>
            </div>
            <div>
                <label class="form-label" for="confirm-password">Confirm new password</label>
                <input class="form-control" id="confirm-password" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" required>
            </div>
            <button class="btn trainer-primary-btn" type="submit">Update password</button>
        </form>
    </section>
@endsection
