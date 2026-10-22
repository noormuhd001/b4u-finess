<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badges;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BadgeManagementController extends Controller
{
    public function index()
    {
        $badges = Badges::with('users:id')->withCount('users')->orderBy('name')->paginate(12);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.badges.index', compact('badges', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $userIds = $validated['users'] ?? [];
        unset($validated['users']);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $this->storeIcon($request);
        }

        $badge = Badges::create($validated);
        $badge->users()->sync($userIds);

        return redirect()->route('admin.badges.index')->with('success', 'Badge created successfully.');
    }

    public function update(Request $request, Badges $badge)
    {
        $validated = $this->validatedData($request);
        $userIds = $validated['users'] ?? [];
        unset($validated['users']);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $this->storeIcon($request);
        } else {
            unset($validated['icon']);
        }

        $badge->update($validated);
        $badge->users()->sync($userIds);

        return redirect()->route('admin.badges.index')->with('success', 'Badge and assignments updated successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'image', 'max:5120'],
            'users' => ['nullable', 'array'],
            'users.*' => ['integer', Rule::exists('users', 'id')],
        ]);
    }

    private function storeIcon(Request $request): string
    {
        $file = $request->file('icon');
        $filename = $file->hashName();
        $file->move(public_path('img'), $filename);

        return 'img/'.$filename;
    }
}
