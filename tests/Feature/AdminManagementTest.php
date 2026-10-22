<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_management_screens(): void
    {
        $admin = User::factory()->create(['role' => 1]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Good to see you');

        $this->actingAs($admin)
            ->get(route('admin.profile.index'))
            ->assertOk()
            ->assertSee('ADMIN CONSOLE')
            ->assertSee(route('admin.profile.update'));

        $this->actingAs($admin)
            ->get(route('admin.user-management.index'))
            ->assertOk()
            ->assertSee('User management')
            ->assertDontSee('Members');

        $this->actingAs($admin)->get(route('admin.workouts.index'))->assertOk()->assertSee('Workout library');
        $this->actingAs($admin)->get(route('admin.badges.index'))->assertOk()->assertSee('Badges & rewards');
        $this->get('/admin/progress/reviews')->assertNotFound();

        $user = User::factory()->create(['role' => 0]);
        $this->actingAs($admin)
            ->get(route('admin.user-management.show', $user))
            ->assertOk()
            ->assertSee('Progress history');
    }

    public function test_admin_profile_requires_admin_authentication(): void
    {
        $this->get(route('admin.profile.index'))->assertRedirect(route('login'));

        $member = User::factory()->create(['role' => 0]);

        $this->actingAs($member)
            ->get(route('admin.profile.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_create_member_account(): void
    {
        $admin = User::factory()->create(['role' => 1]);

        $this->actingAs($admin)->post(route('admin.user-management.store'), [
            'name' => 'New Member',
            'email' => 'new-member@example.com',
            'password' => 'secure-password',
            'role' => '0',
        ])->assertRedirect(route('admin.user-management.index'));

        $member = User::where('email', 'new-member@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('secure-password', $member->password));
        $this->assertTrue($member->status);
    }

    public function test_admin_can_filter_workouts_by_body_part(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        Workout::create([
            'workout_name' => 'Push Ups',
            'body_part' => 'Chest',
            'kcal_per_minute' => 8,
            'image' => 'workouts/push-ups.jpg',
        ]);
        Workout::create([
            'workout_name' => 'Squats',
            'body_part' => 'Legs',
            'kcal_per_minute' => 8,
            'image' => 'workouts/squats.jpg',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.workouts.index', ['body_part' => 'Chest']))
            ->assertOk()
            ->assertSee('Push Ups')
            ->assertDontSee('Squats')
            ->assertSee('value="Chest" selected', false);
    }

    public function test_admin_can_create_workout_with_a_body_part_category(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 1]);

        $this->actingAs($admin)->post(route('admin.workouts.store'), [
            'workout_name' => 'Goblet Squat',
            'body_part' => 'Legs',
            'kcal_per_minute' => 7,
            'image' => UploadedFile::fake()->image('goblet-squat.jpg'),
        ])->assertRedirect(route('admin.workouts.index'));

        $this->assertDatabaseHas('workouts', [
            'workout_name' => 'Goblet Squat',
            'body_part' => 'Legs',
        ]);
    }

    public function test_inactive_account_cannot_sign_in(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('secure-password'),
            'status' => false,
        ]);

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'secure-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
