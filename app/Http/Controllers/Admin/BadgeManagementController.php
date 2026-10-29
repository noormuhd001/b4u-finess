<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badges;
use Illuminate\Http\Request;

class BadgeManagementController extends Controller
{
    public function index()
    {
        $badges = Badges::orderBy('name')->paginate(12);

        return view('admin.badges.index', compact('badges'));
    }

    public function update(Request $request, Badges $badge)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $this->storeIcon($request);
        } else {
            unset($validated['icon']);
        }

        $badge->update($validated);

        return redirect()->route('admin.badges.index')->with('success', 'Badge updated successfully.');
    }

    private function storeIcon(Request $request): string
    {
        $file = $request->file('icon');
        $filename = $file->hashName();
        $file->move(public_path('img'), $filename);

        return 'img/'.$filename;
    }
}
