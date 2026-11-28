<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipOffer;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class MembershipOfferController extends Controller
{
    private const DURATIONS = [1, 3, 6, 12];

    public function index()
    {
        return redirect()->to(route('admin.membership-plans.index').'#membership-offers');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $planIds = $validated['plan_ids'];
        unset($validated['plan_ids']);

        $offer = MembershipOffer::create($validated);
        $offer->plans()->sync($planIds);

        return redirect()->route('admin.membership-offers.index')
            ->with('success', 'Membership offer created successfully.');
    }

    public function update(Request $request, MembershipOffer $membershipOffer)
    {
        $validated = $this->validatedData($request, $membershipOffer);
        $planIds = $validated['plan_ids'];
        unset($validated['plan_ids']);

        $membershipOffer->update($validated);
        $membershipOffer->plans()->sync($planIds);

        return redirect()->route('admin.membership-offers.index')
            ->with('success', 'Membership offer updated successfully.');
    }

    public function updateStatus(Request $request, MembershipOffer $membershipOffer)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $membershipOffer->update(['is_active' => $validated['is_active']]);

        return redirect()->route('admin.membership-offers.index')->with(
            'success',
            $membershipOffer->is_active ? 'Membership offer activated.' : 'Membership offer deactivated.'
        );
    }

    private function validatedData(Request $request, ?MembershipOffer $membershipOffer = null): array
    {
        $request->merge([
            'coupon_code' => $request->filled('coupon_code')
                ? strtoupper(trim($request->input('coupon_code')))
                : null,
        ]);

        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
                'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
                'discount_value' => [
                    'required',
                    'numeric',
                    'gt:0',
                    'max:99999999.99',
                    ...($request->input('discount_type') === 'percentage' ? ['max:100'] : []),
                ],
                'plan_ids' => ['required', 'array', 'min:1'],
                'plan_ids.*' => ['required', 'integer', Rule::exists('membership_plans', 'id')],
                'applicable_durations' => ['required', 'array', 'min:1'],
                'applicable_durations.*' => ['required', 'integer', Rule::in(self::DURATIONS)],
                'starts_at' => ['required', 'date'],
                'expires_at' => ['required', 'date', 'after_or_equal:starts_at'],
                'coupon_code' => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('membership_offers', 'coupon_code')->ignore($membershipOffer?->id),
                ],
                'maximum_usage' => [
                    'nullable',
                    'integer',
                    'min:1',
                    function (string $attribute, mixed $value, \Closure $fail) use ($membershipOffer): void {
                        if ($membershipOffer && $value !== null && (int) $value < $membershipOffer->usage_count) {
                            $fail('The limit cannot be lower than the number of offers already used.');
                        }
                    },
                ],
                'is_active' => ['required', 'boolean'],
            ]);
        } catch (ValidationException $exception) {
            $exception->redirectTo(route('admin.membership-plans.index').'#membership-offers');
            throw $exception;
        }

        $validated['applicable_durations'] = array_values(array_unique(array_map(
            'intval',
            $validated['applicable_durations']
        )));

        return $validated;
    }
}
