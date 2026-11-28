<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TrainerManagementController extends Controller
{
    public function index()
    {
        $trainers = Trainer::withCount('users')->orderBy('name')->paginate(12);

        return view('admin.trainers.index', compact('trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('trainers', 'public');
        }

        Trainer::create($validated);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer added successfully.');
    }

    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate($this->rules($trainer));

        if ($request->hasFile('photo')) {
            $newPhoto = $request->file('photo')->store('trainers', 'public');
            if ($trainer->photo) {
                Storage::disk('public')->delete($trainer->photo);
            }
            $validated['photo'] = $newPhoto;
        } else {
            unset($validated['photo']);
        }

        $trainer->update($validated);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer details updated successfully.');
    }

    public function destroy(Trainer $trainer)
    {
        $photo = $trainer->photo;
        $trainer->delete();

        if ($photo) {
            Storage::disk('public')->delete($photo);
        }

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer removed successfully.');
    }

    private function rules(?Trainer $trainer = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('trainers', 'email')->ignore($trainer?->id)],
            'password' => [$trainer ? 'nullable' : 'required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'specialty' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:100'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
