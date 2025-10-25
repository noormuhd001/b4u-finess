@extends('layouts.app')

@push('styles')
    <style>
        .d-flex.justify-content-end.gap-2 {
            margin-top: 1rem;
        }

        #editMode .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
            color: #007bff;
        }
    </style>
@endpush

@section('content')
    <div class="container my-5">
        <div class="profile-card">
            <!-- Edit Button -->
            <button class="btn btn-outline-primary edit-btn" id="editToggle" title="Edit Profile">
                <span class="material-symbols-outlined">edit</span>
            </button>

            <!-- Profile Header -->
            <div class="profile-header">
                <img id="profilePreview"
                    src="{{ $user->image ? asset('storage/' . $user->image) : asset('img/profile.jpeg') }}"
                    alt="Profile Picture">

                <h2>{{ $user->name }}</h2>
                <p class="text-muted">{{ $goals[$user->goal] ?? 'No Goal Set' }}</p>
            </div>

            <!-- View Mode -->
            <div id="viewMode" class="{{ $errors->any() ? 'd-none' : '' }}">
                <div class="profile-info">
                    <p><span class="material-symbols-outlined text-info">mail</span> Email: {{ $user->email }}</p>
                    <p><span class="material-symbols-outlined text-warning">person</span> Gender:
                        {{ $genders[$user->gender] ?? 'N/A' }}</p>
                    <p><span class="material-symbols-outlined text-success">straighten</span> Height:
                        {{ $user->height ?? 'N/A' }} cm</p>
                    <p><span class="material-symbols-outlined text-danger">monitor_weight</span> Weight:
                        {{ $user->weight ?? 'N/A' }} kg</p>
                </div>
            </div>

            <!-- Edit Mode -->
            <div id="editMode" class="{{ $errors->any() ? '' : 'd-none' }}">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">

                    <!-- Profile Photo -->
                    <div class="mb-3 ">
                        <label class="form-label">
                            <span class="material-symbols-outlined">photo_camera</span> Profile Photo
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
                        <label class="form-label"><span class="material-symbols-outlined">person</span> Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label"><span class="material-symbols-outlined">mail</span> Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label class="form-label"><span class="material-symbols-outlined">man</span> Gender</label>
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
                        <label class="form-label"><span class="material-symbols-outlined">straighten</span> Height
                            (cm)</label>
                        <input type="number" name="height" class="form-control @error('height') is-invalid @enderror"
                            value="{{ old('height', $user->height) }}">
                        @error('height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Weight -->
                    <div class="mb-3">
                        <label class="form-label"><span class="material-symbols-outlined">monitor_weight</span> Weight
                            (kg)</label>
                        <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror"
                            value="{{ old('weight', $user->weight) }}">
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Goal -->
                    <div class="mb-3">
                        <label class="form-label"><span class="material-symbols-outlined">track_changes</span> Goal</label>
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

                    <div class="d-flex justify-content-end gap-2">
                        <!-- Buttons -->
                        <button type="submit" class="btn btn-save">
                            <span class="material-symbols-outlined">check_circle</span> Save
                        </button>
                        <button type="button" class="btn btn-cancel" id="cancelEdit">
                            <span class="material-symbols-outlined">cancel</span> Cancel
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
                    output.src = "{{ asset('img/profile.jpeg') }}";
                }
            }
        </script>
    @endpush
@endsection
