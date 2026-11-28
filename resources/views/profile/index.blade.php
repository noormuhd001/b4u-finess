@extends($isAdmin ? 'layouts.admin.app' : 'layouts.app')

@section('title', 'My profile')
@section('page-heading', 'My profile')

@section('content')
    @php
        $profileEditHasErrors = $errors->hasAny(['profile_picture', 'name', 'email', 'gender', 'height', 'weight', 'goal']);
    @endphp

    <div class="container">
        <div class="user-page-heading">
            <div><h1>My profile</h1><p>{{ $isAdmin ? 'Update your account details and profile photo.' : 'Keep your personal details and fitness goals up to date.' }}</p></div>
        </div>
        <div class="profile-card">
            <!-- Edit Button -->
            <button class="btn btn-outline-secondary edit-btn" id="editToggle" type="button" title="Edit Profile" aria-label="Edit profile">
                <i class="bi bi-pencil" aria-hidden="true"></i>
            </button>

            <!-- Profile Header -->
            <div class="profile-header">
                <img id="profilePreview"
                    src="{{ $user->image ? asset('storage/' . $user->image) : asset('img/profile.png') }}"
                    alt="Profile Picture">

                <h2>{{ $user->name }}</h2>
                @unless ($isAdmin)
                    <p class="text-muted">{{ $goals[$user->goal] ?? 'No Goal Set' }}</p>
                @endunless
            </div>

            <!-- View Mode -->
            <div id="viewMode" class="{{ $profileEditHasErrors ? 'd-none' : '' }}">
                <div class="profile-info">
                    <p><i class="bi bi-envelope text-info" aria-hidden="true"></i> Email: {{ $user->email }}</p>
                    @unless ($isAdmin)
                        <p><i class="bi bi-card-checklist text-success" aria-hidden="true"></i> Membership plan:
                            {{ $user->membershipPlan?->name ?? 'No plan assigned' }}
                            @if ($user->membershipPlan)
                                · {{ $user->membership_duration_months ?: 1 }} {{ \Illuminate\Support\Str::plural('month', $user->membership_duration_months ?: 1) }}
                                · Amount due ₹{{ number_format((float) ($user->membership_final_price ?? ($user->membershipPlan->price * ($user->membership_duration_months ?: 1))), 2) }}
                                @if ((float) ($user->membership_discount_amount ?? 0) > 0)
                                    · Saved ₹{{ number_format((float) $user->membership_discount_amount, 2) }}{{ $user->membershipOffer ? ' with '.$user->membershipOffer->title : '' }}
                                @endif
                            @endif
                        </p>
                        <p><i class="bi bi-calendar-event text-success" aria-hidden="true"></i> Membership valid until:
                            {{ $user->membership_expires_at?->format('M d, Y') ?? 'Not active' }}
                        </p>
                        <p><i class="bi bi-cash-coin text-warning" aria-hidden="true"></i> Cash payment:
                            {{ ucfirst($user->cash_payment_status ?? 'pending') }}
                        </p>
                        <p><i class="bi bi-person text-warning" aria-hidden="true"></i> Gender:
                            {{ $genders[$user->gender] ?? 'N/A' }}</p>
                        <p><i class="bi bi-arrows-vertical text-success" aria-hidden="true"></i> Height:
                            {{ $user->height ?? 'N/A' }} cm</p>
                        <p><i class="bi bi-speedometer2 text-danger" aria-hidden="true"></i> Weight:
                            {{ $user->weight ?? 'N/A' }} kg</p>
                    @endunless
                </div>
            </div>

            <!-- Edit Mode -->
            <div id="editMode" class="{{ $profileEditHasErrors ? '' : 'd-none' }}">
                <form method="POST" action="{{ $profileUpdateRoute }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Profile Photo -->
                    <div class="mb-3 ">
                        <label class="form-label" for="profile-picture">
                            <i class="bi bi-camera" aria-hidden="true"></i> Profile Photo
                        </label>
                        <input id="profile-picture" type="file" name="profile_picture"
                            class="form-control @error('profile_picture') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif"
                            aria-describedby="profile-picture-help profile-picture-error"
                            data-max-bytes="2097152">
                        <small id="profile-picture-help" class="form-text text-muted">JPG, PNG, or GIF; maximum size 2 MB.</small>

                        <div id="profile-picture-error" class="invalid-feedback" role="alert">@error('profile_picture'){{ $message }}@enderror</div>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-person" aria-hidden="true"></i> Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-envelope" aria-hidden="true"></i> Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @unless ($isAdmin)
                        <!-- Gender -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-person" aria-hidden="true"></i> Gender</label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                @foreach ($genders as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('gender', $user->gender) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Height -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-arrows-vertical" aria-hidden="true"></i> Height
                                (cm)</label>
                            <input type="number" name="height" class="form-control @error('height') is-invalid @enderror"
                                value="{{ old('height', $user->height) }}">
                            @error('height')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-speedometer2" aria-hidden="true"></i> Weight
                                (kg)</label>
                            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror"
                                value="{{ old('weight', $user->weight) }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Goal -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-bullseye" aria-hidden="true"></i> Goal</label>
                            <select name="goal" class="form-control @error('goal') is-invalid @enderror">
                                @foreach ($goals as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('goal', $user->goal) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('goal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endunless

                    <div class="profile-form-actions d-flex justify-content-end gap-2">
                        <!-- Buttons -->
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check-circle" aria-hidden="true"></i> Save
                        </button>
                        <button type="button" class="btn btn-cancel" id="cancelEdit">
                            <i class="bi bi-x-circle" aria-hidden="true"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <section class="profile-card password-change-card" aria-labelledby="password-change-heading">
            <h2 id="password-change-heading">Change password</h2>
            <p class="text-muted">Enter your current password and choose a new one.</p>

            <form method="POST" action="{{ $passwordUpdateRoute }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="current-password">Current password</label>
                    <input id="current-password" type="password" name="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        autocomplete="current-password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="new-password">New password</label>
                    <input id="new-password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="new-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password-confirmation">Confirm new password</label>
                    <input id="password-confirmation" type="password" name="password_confirmation"
                        class="form-control" autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn btn-save">Update password</button>
            </form>
        </section>
    </div>

    @push('scripts')
        <script src="{{ asset('js/profile.js') }}" defer></script>
    @endpush
@endsection
