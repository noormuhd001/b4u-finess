<?php

namespace App\Services;

use App\Models\MembershipOffer;
use App\Models\MembershipPlan;
use Illuminate\Validation\ValidationException;

class MembershipPricing
{
    public function findApplicableOffer(
        MembershipPlan $plan,
        int $durationMonths,
        ?int $offerId,
        ?string $couponCode,
        bool $lockForUpdate = false,
    ): ?MembershipOffer {
        $couponCode = $couponCode !== null ? strtoupper(trim($couponCode)) : null;

        if ($offerId !== null && $couponCode !== null && $couponCode !== '') {
            throw ValidationException::withMessages([
                'coupon_code' => 'Choose an offer or enter a coupon code, not both.',
            ]);
        }

        if ($offerId === null && ($couponCode === null || $couponCode === '')) {
            return null;
        }

        $query = MembershipOffer::query()->with('plans');
        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        if ($offerId !== null) {
            $query->whereKey($offerId);
            $errorField = 'offer_id';
        } else {
            $query->whereRaw('UPPER(coupon_code) = ?', [$couponCode]);
            $errorField = 'coupon_code';
        }

        $offer = $query->first();
        if (! $offer || ! $offer->isApplicableTo($plan, $durationMonths)) {
            throw ValidationException::withMessages([
                $errorField => 'This offer is not available for the selected plan and subscription duration.',
            ]);
        }

        return $offer;
    }

    public function quote(MembershipPlan $plan, int $durationMonths, ?MembershipOffer $offer = null): array
    {
        $subtotalCents = (int) round((float) $plan->price * $durationMonths * 100);
        $discountCents = 0;

        if ($offer) {
            $discountCents = $offer->discount_type === 'percentage'
                ? (int) round($subtotalCents * (float) $offer->discount_value / 100)
                : (int) round((float) $offer->discount_value * 100);
            $discountCents = min($subtotalCents, $discountCents);
        }

        return [
            'subtotal' => $this->formatAmount($subtotalCents),
            'discount' => $this->formatAmount($discountCents),
            'total' => $this->formatAmount($subtotalCents - $discountCents),
            'offer_id' => $offer?->id,
            'offer_title' => $offer?->title,
        ];
    }

    private function formatAmount(int $amountInCents): string
    {
        return number_format($amountInCents / 100, 2, '.', '');
    }
}
