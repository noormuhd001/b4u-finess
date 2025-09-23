@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Profile</h1>

        <!-- Global Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm h-100 position-relative">
                    <div class="card-body">
                        <div class="view-mode {{ $errors->any() ? 'd-none' : '' }}">
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <p class="card-text mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="card-text mb-1"><strong>Gender:</strong> {{ $genders[$user->gender] ?? 'N/A' }}</p>
                            <p class="card-text mb-1"><strong>Height:</strong> {{ $user->height ?? 'N/A' }}</p>
                            <p class="card-text mb-1"><strong>Weight:</strong> {{ $user->weight ?? 'N/A' }}</p>

                            <p class="card-text"><strong>Goal:</strong> {{ $goals[$user->goal] ?? 'N/A' }}</p>
                        </div>

                        <form class="edit-mode {{ $errors->any() ? '' : 'd-none' }}" method="POST"
                            action="{{ route('profile.update') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <!-- Name -->
                            <div class="mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="mb-2">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                    @foreach ($genders as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('gender', $user->gender) == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Goal -->
                            <div class="mb-2">
                                <label class="form-label">Goal</label>
                                <select name="goal" class="form-control @error('goal') is-invalid @enderror">
                                    @foreach ($goals as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('goal', $user->goal) == $key ? 'selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('goal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Height -->
                            <div class="mb-2">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" name="height"
                                    class="form-control @error('height') is-invalid @enderror"
                                    value="{{ old('height', $user->height) }}">
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Weight -->
                            <div class="mb-2">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" name="weight"
                                    class="form-control @error('weight') is-invalid @enderror"
                                    value="{{ old('weight', $user->weight) }}">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success btn-sm">Save</button>
                            <button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                        </form>
                    </div>


                    <!-- Edit Icon -->
                    <a href="javascript:void(0);"
                        class="position-absolute top-0 end-0 m-2 btn btn-sm btn-outline-primary edit-btn"
                        title="Edit Profile">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card');
            const editBtn = card.querySelector('.edit-btn');
            const cancelBtn = card.querySelector('.cancel-edit');
            const viewMode = card.querySelector('.view-mode');
            const editMode = card.querySelector('.edit-mode');

            editBtn.addEventListener('click', function() {
                viewMode.classList.add('d-none');
                editMode.classList.remove('d-none');
            });

            cancelBtn.addEventListener('click', function() {
                editMode.classList.add('d-none');
                viewMode.classList.remove('d-none');
            });
        });
    </script>
@endsection
