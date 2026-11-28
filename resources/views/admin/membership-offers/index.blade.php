@extends('layouts.admin.app')

@section('title', 'Membership offers')
@section('page-heading', 'Membership offers')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Membership offers</h1>
            <p>Create time-limited discounts for selected plans and subscription lengths.</p>
        </div>
        <div class="page-heading-actions">
            <button class="admin-btn" type="button" data-bs-toggle="modal" data-bs-target="#createOfferModal">
                <i class="bi bi-plus-lg"></i> Add offer
            </button>
        </div>
    </div>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2>Offer catalog</h2>
                <p>Offers are only available during their date range and while usage remains.</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr><th>Offer</th><th>Discount</th><th>Applies to</th><th>Dates</th><th>Usage</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($offers as $offer)
                        <tr>
                            <td>
                                <span class="entity-name">{{ $offer->title }}</span>
                                <span class="entity-meta">{{ $offer->coupon_code ? 'Code: '.$offer->coupon_code : 'Automatic offer' }}</span>
                            </td>
                            <td>{{ $offer->discount_type === 'percentage' ? rtrim(rtrim(number_format((float) $offer->discount_value, 2), '0'), '.').'%' : '₹'.number_format((float) $offer->discount_value, 2) }}</td>
                            <td>
                                <span class="entity-name">{{ $offer->plans->pluck('name')->join(', ') }}</span>
                                <span class="entity-meta">{{ collect($offer->applicable_durations)->map(fn ($months) => $months.' '.\Illuminate\Support\Str::plural('month', $months))->join(', ') }}</span>
                            </td>
                            <td>{{ $offer->starts_at->format('M d, Y') }} – {{ $offer->expires_at->format('M d, Y') }}</td>
                            <td>{{ number_format($offer->usage_count) }} / {{ $offer->maximum_usage ? number_format($offer->maximum_usage) : '∞' }}</td>
                            <td><span class="state-badge {{ $offer->is_active ? '' : 'inactive' }}">{{ $offer->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="table-actions justify-content-end">
                                    <button class="icon-action" type="button" title="Edit offer" aria-label="Edit {{ $offer->title }}" data-bs-toggle="modal" data-bs-target="#editOffer{{ $offer->id }}"><i class="bi bi-pencil"></i></button>
                                    <form method="POST" action="{{ route('admin.membership-offers.status', $offer) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $offer->is_active ? 0 : 1 }}">
                                        <button class="icon-action" type="submit" title="{{ $offer->is_active ? 'Deactivate' : 'Activate' }} offer" aria-label="{{ $offer->is_active ? 'Deactivate' : 'Activate' }} {{ $offer->title }}">
                                            <i class="bi {{ $offer->is_active ? 'bi-pause-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="table-empty"><i class="bi bi-ticket-perforated"></i>No offers yet. Add an offer to make discounts available at checkout.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal fade" id="createOfferModal" tabindex="-1" aria-labelledby="createOfferTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" method="POST" action="{{ route('admin.membership-offers.store') }}">
                @csrf
                <div class="modal-header"><div><h2 class="modal-title" id="createOfferTitle">Add membership offer</h2><p class="text-secondary small mb-0 mt-1">Choose which memberships and durations qualify.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body">
                    @include('admin.membership-offers._fields', ['offer' => null, 'prefix' => 'new'])
                </div>
                <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Create offer</button></div>
            </form>
        </div>
    </div>

    @foreach ($offers as $offer)
        <div class="modal fade" id="editOffer{{ $offer->id }}" tabindex="-1" aria-labelledby="editOfferTitle{{ $offer->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <form class="modal-content" method="POST" action="{{ route('admin.membership-offers.update', $offer) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header"><div><h2 class="modal-title" id="editOfferTitle{{ $offer->id }}">Edit membership offer</h2><p class="text-secondary small mb-0 mt-1">{{ number_format($offer->usage_count) }} redemptions recorded</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body">
                        @include('admin.membership-offers._fields', ['offer' => $offer, 'prefix' => 'offer'.$offer->id])
                    </div>
                    <div class="modal-footer"><button type="button" class="admin-btn admin-btn-light" data-bs-dismiss="modal">Cancel</button><button class="admin-btn" type="submit">Save changes</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
