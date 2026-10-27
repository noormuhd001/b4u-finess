@extends($isAdmin ? 'layouts.admin.app' : 'layouts.app')

@section('title', 'My profile')
@section('page-heading', 'My profile')

@section('content')
    <div class="container">
        <div class="user-page-heading">
            <div><h1>My profile</h1><p>Keep your personal details and fitness goals up to date.</p></div>
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
                <p class="text-muted">{{ $goals[$user->goal] ?? 'No Goal Set' }}</p>
            </div>

            <!-- View Mode -->
            <div id="viewMode" class="{{ $errors->any() ? 'd-none' : '' }}">
                <div class="profile-info">
                    <p><i class="bi bi-envelope text-info" aria-hidden="true"></i> Email: {{ $user->email }}</p>
                    <p><i class="bi bi-card-checklist text-success" aria-hidden="true"></i> Membership plan:
                        {{ $user->membershipPlan?->name ?? 'No plan assigned' }}
                        @if ($user->membershipPlan)
                            · ₹{{ number_format((float) $user->membershipPlan->price, 2) }} / {{ $user->membershipPlan->duration_months }} {{ \Illuminate\Support\Str::plural('month', $user->membershipPlan->duration_months) }}
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
                </div>
            </div>

            <!-- Edit Mode -->
            <div id="editMode" class="{{ $errors->any() ? '' : 'd-none' }}">
                <form method="POST" action="{{ $profileUpdateRoute }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Profile Photo -->
                    <div class="mb-3 ">
                        <label class="form-label">
                            <i class="bi bi-camera" aria-hidden="true"></i> Profile Photo
                        </label>
                        <input type="file" name="profile_picture"
                            class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*"
                            onchange="previewImage(event)">

                        @error('profile_picture')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
    </div>

    @push('scripts')
        <script>
            const editToggle = document.getElementById('editToggle');
            const cancelEdit = document.getElementById('cancelEdit');
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');

            editToggle.addEventListener('click', () => {
                viewMode.classList.add('d-none');
                editMode.classList.remove('d-none');
            });

            cancelEdit.addEventListener('click', () => {
                editMode.classList.add('d-none');
                viewMode.classList.remove('d-none');
            });

            function previewImage(event) {
                const output = document.getElementById('profilePreview');
                if (event.target.files[0]) {
                    output.src = URL.createObjectURL(event.target.files[0]);
                } else {
                    output.src = "{{ asset('img/profile.png') }}";
                }
            }
        </script>
    @endpush
@endsection
