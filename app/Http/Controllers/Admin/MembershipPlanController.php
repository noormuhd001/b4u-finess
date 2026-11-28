<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipOffer;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::withCount('users')->orderBy('name')->get();
        $offers = MembershipOffer::with('plans')->orderByDesc('created_at')->get();

        return view('admin.membership-plans.index', compact('plans', 'offers'));
    }

    public function store(Request $request)
    {
        MembershipPlan::create($this->validatedData($request));

        return redirect()->route('admin.membership-plans.index')->with('success', 'Membership plan created successfully.');
    }

    public function update(Request $request, MembershipPlan $membershipPlan)
    {
        $membershipPlan->update($this->validatedData($request, $membershipPlan));

        return redirect()->route('admin.membership-plans.index')->with('success', 'Membership plan updated successfully.');
    }

    public function updateStatus(Request $request, MembershipPlan $membershipPlan)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $membershipPlan->update(['is_active' => $validated['is_active']]);

        return redirect()->route('admin.membership-plans.index')->with(
            'success',
            $membershipPlan->is_active ? 'Membership plan activated.' : 'Membership plan deactivated.'
        );
    }

    private function validatedData(Request $request, ?MembershipPlan $membershipPlan = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_plans', 'name')->ignore($membershipPlan?->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'features_text' => ['nullable', 'string', 'max:2000'],
            'duration_months' => ['sometimes', 'required', 'integer', 'min:1', 'max:120'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['features'] = array_values(array_filter(array_map(
            'trim',
            preg_split('/\R/u', $validated['features_text'] ?? '') ?: []
        )));
        unset($validated['features_text']);
        $validated['duration_months'] = 1;

        return $validated;
    }
}
