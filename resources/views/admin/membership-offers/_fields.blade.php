@php
    $selectedPlans = old('plan_ids', $offer?->plans->pluck('id')->all() ?? []);
    $selectedDurations = old('applicable_durations', $offer?->applicable_durations ?? [1, 3, 6, 12]);
    $activeValue = (string) old('is_active', $offer?->is_active === null ? '1' : (string) (int) $offer->is_active);
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-title">Offer title</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-title" name="title" value="{{ old('title', $offer?->title) }}" required maxlength="255">
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-coupon">Coupon code (optional)</label>
        <input class="form-control admin-form-control text-uppercase" id="{{ $prefix }}-coupon" name="coupon_code" value="{{ old('coupon_code', $offer?->coupon_code) }}" maxlength="50" autocomplete="off">
    </div>
    <div class="col-12">
        <label class="admin-form-label" for="{{ $prefix }}-description">Description</label>
        <textarea class="form-control admin-form-control" id="{{ $prefix }}-description" name="description" rows="2" maxlength="1000">{{ old('description', $offer?->description) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-discount-type">Discount type</label>
        <select class="form-select admin-form-control" id="{{ $prefix }}-discount-type" name="discount_type" required>
            <option value="percentage" @selected(old('discount_type', $offer?->discount_type) === 'percentage')>Percentage</option>
            <option value="fixed" @selected(old('discount_type', $offer?->discount_type) === 'fixed')>Fixed amount (₹)</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-discount-value">Discount value</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-discount-value" name="discount_value" type="number" min="0.01" step="0.01" value="{{ old('discount_value', $offer?->discount_value) }}" required>
        <small class="entity-meta">Percentage discounts cannot exceed 100%.</small>
    </div>
    <div class="col-md-6">
        <span class="admin-form-label d-block">Applicable membership plans</span>
        <div class="d-flex flex-wrap gap-3">
            @foreach ($plans as $plan)
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="plan_ids[]" value="{{ $plan->id }}" @checked(in_array($plan->id, $selectedPlans))>
                    <span class="form-check-label">{{ $plan->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
    <div class="col-md-6">
        <span class="admin-form-label d-block">Applicable subscription durations</span>
        <div class="d-flex flex-wrap gap-3">
            @foreach ([1, 3, 6, 12] as $months)
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="applicable_durations[]" value="{{ $months }}" @checked(in_array($months, $selectedDurations))>
                    <span class="form-check-label">{{ $months }} {{ \Illuminate\Support\Str::plural('month', $months) }}</span>
                </label>
            @endforeach
        </div>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-starts">Offer starts</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-starts" name="starts_at" type="date" value="{{ old('starts_at', $offer?->starts_at?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-expires">Offer expiry</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-expires" name="expires_at" type="date" value="{{ old('expires_at', $offer?->expires_at?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-maximum">Maximum usage (optional)</label>
        <input class="form-control admin-form-control" id="{{ $prefix }}-maximum" name="maximum_usage" type="number" min="{{ $offer ? max(1, $offer->usage_count) : 1 }}" value="{{ old('maximum_usage', $offer?->maximum_usage) }}">
    </div>
    <div class="col-md-6">
        <label class="admin-form-label" for="{{ $prefix }}-active">Availability</label>
        <select class="form-select admin-form-control" id="{{ $prefix }}-active" name="is_active" required>
            <option value="1" @selected($activeValue === '1')>Active</option>
            <option value="0" @selected($activeValue === '0')>Inactive</option>
        </select>
    </div>
</div>
