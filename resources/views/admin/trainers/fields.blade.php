<div class="row g-3">
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-name">Full name</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-name" name="name" value="{{ old('name', $trainer?->name) }}" required maxlength="255">
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-specialty">Specialty</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-specialty" name="specialty" value="{{ old('specialty', $trainer?->specialty) }}" required maxlength="255" placeholder="Strength training">
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-email">Email address</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-email" name="email" type="email" value="{{ old('email', $trainer?->email) }}" required maxlength="255">
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-password">{{ $trainer ? 'Reset password (optional)' : 'Temporary password' }}</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-password" name="password" type="password" {{ $trainer ? '' : 'required' }} minlength="8" autocomplete="new-password">
        <small class="entity-meta mt-1">{{ $trainer ? 'Leave blank to keep the current password.' : 'The trainer can change this after signing in.' }}</small>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-phone">Phone number</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-phone" name="phone" type="tel" value="{{ old('phone', $trainer?->phone) }}" maxlength="30">
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-experience">Years of experience</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-experience" name="experience_years" type="number" min="0" max="100" value="{{ old('experience_years', $trainer?->experience_years ?? 0) }}" required>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-photo">Profile photo</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-trainer-photo" name="photo" type="file" accept="image/*">
        <small class="entity-meta mt-1">Optional · maximum file size: 5 MB.</small>
    </div>
    <div class="col-12">
        <label class="admin-form-label" for="{{ $prefix }}-trainer-bio">Bio</label>
        <textarea class="form-control admin-form-control" id="{{ $prefix }}-trainer-bio" name="bio" rows="3" maxlength="5000">{{ old('bio', $trainer?->bio) }}</textarea>
    </div>
    @if ($trainer)
        <div class="col-md-6">
            <label class="admin-form-label" for="{{ $prefix }}-trainer-status">Account access</label>
            <select class="form-select admin-form-control" id="{{ $prefix }}-trainer-status" name="is_active" required>
                <option value="1" @selected(old('is_active', $trainer->is_active))>Active</option>
                <option value="0" @selected(! old('is_active', $trainer->is_active))>Inactive</option>
            </select>
        </div>
    @else
        <input type="hidden" name="is_active" value="1">
    @endif
</div>
