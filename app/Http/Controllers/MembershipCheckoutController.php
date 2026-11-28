<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Services\MembershipPricing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MembershipCheckoutController extends Controller
{
    public function quote(Request $request, MembershipPricing $pricing)
    {
        $validated = $request->validate([
            'plan_id' => [
                'required',
                'integer',
                Rule::exists('membership_plans', 'id')->where('is_active', true),
            ],
            'duration_months' => ['required', 'integer', Rule::in([1, 3, 6, 12])],
            'offer_id' => ['nullable', 'integer', Rule::exists('membership_offers', 'id')],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        $plan = MembershipPlan::findOrFail($validated['plan_id']);
        $offer = $pricing->findApplicableOffer(
            $plan,
            (int) $validated['duration_months'],
            isset($validated['offer_id']) ? (int) $validated['offer_id'] : null,
            $validated['coupon_code'] ?? null,
        );

        return response()->json($pricing->quote($plan, (int) $validated['duration_months'], $offer));
    }
}
