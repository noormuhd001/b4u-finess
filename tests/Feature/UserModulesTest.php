<?php

namespace Tests\Feature;

use App\Jobs\Auth\SendForgotPasswordJob;
use App\Models\Badges;
use App\Models\Progress;
use App\Models\ProgressLog;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_only_view_their_own_progress_details(): void
    {
        $member = User::factory()->create(['role' => 0]);
        $anotherMember = User::factory()->create(['role' => 0]);
        $ownProgress = $this->createProgress($member, today()->toDateString());
        $otherProgress = $this->createProgress($anotherMember, today()->subDay()->toDateString());

        $this->actingAs($member)
            ->get(route('progress.trackDetailById', $ownProgress))
            ->assertOk()
            ->assertSee($ownProgress->current_weight);

        $this->get(route('progress.trackDetailById', $otherProgress))
            ->assertNotFound();
    }

    public function test_progress_requires_an_exercise_and_saves_its_logs_and_calories(): void
    {
        $member = User::factory()->create(['role' => 0]);
        $workout = $this->createWorkout();
        $date = today()->toDateString();

        $this->actingAs($member)
            ->post(route('progress.store'), [
                'weight' => 70,
                'workout_on' => $date,
            ])
            ->assertSessionHasErrors('workouts_completed');

        $this->assertDatabaseCount('progress', 0);

        $this->post(route('progress.store'), $this->workoutPayload($workout, $date))
            ->assertRedirect(route('progress.index'))
            ->assertSessionHas('success', 'Workout added successfully!');

        $progress = Progress::query()->where('user_id', $member->id)->firstOrFail();
        $this->assertSame(60, (int) $progress->avg_kcal_burned);
        $this->assertDatabaseHas('progress_logs', [
            'progress_id' => $progress->id,
            'user_id' => $member->id,
            'workout_id' => $workout->id,
            'set_number' => 3,
            'reps' => 10,
            'weight' => 20,
            'kcal_burned' => 60,
        ]);
    }

    public function test_member_can_add_another_workout_to_a_saved_daily_progress_entry(): void
    {
        $member = User::factory()->create(['role' => 0]);
        $workout = $this->createWorkout();
        $date = today()->toDateString();
        $progress = $this->createProgress($member, $date);
        ProgressLog::create([
            'progress_id' => $progress->id,
            'user_id' => $member->id,
            'workout_id' => $workout->id,
            'set_number' => 3,
            'reps' => 10,
            'weight' => 20,
            'kcal_burned' => 60,
        ]);
        $progress->update(['avg_kcal_burned' => 60]);

        $this->actingAs($member)
            ->get(route('progress.index'))
            ->assertOk()
            ->assertSee('Add another workout');

        $this->post(route('progress.store'), $this->workoutPayload($workout, $date))
            ->assertRedirect(route('progress.index'))
            ->assertSessionHas('success', 'Workout added successfully!');

        $this->assertDatabaseCount('progress', 1);
        $this->assertDatabaseCount('progress_logs', 2);
        $this->assertDatabaseHas('progress', [
            'id' => $progress->id,
            'avg_kcal_burned' => 120,
            'current_weight' => 70,
        ]);
    }

    public function test_consistency_badge_requires_seven_distinct_consecutive_workout_days(): void
    {
        Carbon::setTestNow('2026-11-24 12:00:00');

        try {
            $workout = $this->createWorkout();
            $badge = Badges::create(['name' => 'Consistency King']);
            $memberWithGap = User::factory()->create(['role' => 0]);

            foreach ([1, 2, 2, 4, 5, 6] as $daysAgo) {
                $this->createProgress($memberWithGap, today()->subDays($daysAgo)->toDateString());
            }

            $this->actingAs($memberWithGap)
                ->post(route('progress.store'), $this->workoutPayload($workout, today()->toDateString()))
                ->assertRedirect(route('progress.index'));

            $this->assertDatabaseMissing('badge_users', [
                'user_id' => $memberWithGap->id,
                'badge_id' => $badge->id,
            ]);

            $memberWithStreak = User::factory()->create(['role' => 0]);
            foreach (range(1, 6) as $daysAgo) {
                $this->createProgress($memberWithStreak, today()->subDays($daysAgo)->toDateString());
            }

            $this->actingAs($memberWithStreak)
                ->post(route('progress.store'), $this->workoutPayload($workout, today()->toDateString()))
                ->assertRedirect(route('progress.index'));

            $this->assertDatabaseHas('badge_users', [
                'user_id' => $memberWithStreak->id,
                'badge_id' => $badge->id,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_member_can_update_profile_and_dark_appearance_preference(): void
    {
        $member = User::factory()->create(['role' => 0]);

        $this->actingAs($member)
            ->post(route('profile.update'), [
                'name' => 'Updated Member',
                'email' => 'updated-member@example.com',
                'gender' => 2,
                'goal' => 1,
                'height' => 175,
                'weight' => 72,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => 'Updated Member',
            'email' => 'updated-member@example.com',
            'gender' => 2,
            'goal' => 1,
            'height' => 175,
            'weight' => 72,
        ]);

        $this->postJson(route('settings.darkmode'), ['dark_mode' => true])
            ->assertOk()
            ->assertJson(['message' => 'Dark mode updated successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'dark_mode' => true,
        ]);
    }

    public function test_member_can_change_password_from_profile(): void
    {
        $member = User::factory()->create([
            'role' => 0,
            'password' => 'current-password',
        ]);

        $this->actingAs($member)
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Change password')
            ->assertSee(route('profile.password.update'));

        $this->actingAs($member)
            ->from(route('profile.index'))
            ->post(route('profile.password.update'), [
                'current_password' => 'current-password',
                'password' => 'new-member-password',
                'password_confirmation' => 'new-member-password',
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('success', 'Your password has been updated.');

        $this->assertTrue(Hash::check('new-member-password', $member->fresh()->password));
    }

    public function test_member_must_enter_the_current_password_to_change_it(): void
    {
        $member = User::factory()->create([
            'role' => 0,
            'password' => 'current-password',
        ]);

        $this->actingAs($member)
            ->from(route('profile.index'))
            ->post(route('profile.password.update'), [
                'current_password' => 'incorrect-password',
                'password' => 'new-member-password',
                'password_confirmation' => 'new-member-password',
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('current-password', $member->fresh()->password));
    }

    public function test_member_profile_rejects_invalid_and_oversized_profile_pictures(): void
    {
        Storage::fake('public');
        $member = User::factory()->create([
            'role' => 0,
            'image' => 'profile_images/current.png',
        ]);
        Storage::disk('public')->put($member->image, 'existing profile image');

        $payload = [
            'name' => $member->name,
            'email' => $member->email,
            'profile_picture' => UploadedFile::fake()->create('avatar.pdf', 10, 'application/pdf'),
        ];

        $this->actingAs($member)
            ->post(route('profile.update'), $payload)
            ->assertRedirect()
            ->assertSessionHasErrors('profile_picture');

        $this->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Profile picture must be an image.')
            ->assertSee('id="profile-picture-error"', false);

        $payload['profile_picture'] = UploadedFile::fake()->image('oversized.png')->size(2049);

        $this->post(route('profile.update'), $payload)
            ->assertRedirect()
            ->assertSessionHasErrors('profile_picture');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'image' => 'profile_images/current.png',
        ]);
        Storage::disk('public')->assertExists('profile_images/current.png');
    }

    public function test_member_can_open_dashboard_and_workout_library(): void
    {
        $member = User::factory()->create(['role' => 0]);
        $workout = $this->createWorkout();

        $this->actingAs($member)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Good to see you');

        $this->get(route('workout'))
            ->assertOk()
            ->assertSee('Workout library')
            ->assertSee($workout->workout_name);
    }

    public function test_member_can_reset_password_with_a_valid_reset_token(): void
    {
        Queue::fake();
        $member = User::factory()->create(['role' => 0]);

        $this->from(route('forgotPassword'))
            ->post(route('sendOtp'), ['email' => $member->email])
            ->assertRedirect(route('forgotPassword'));

        $token = $member->fresh()->forgot_token;
        $this->assertNotEmpty($token);
        Queue::assertPushed(SendForgotPasswordJob::class);

        $this->get(route('resetPassword', $token))->assertOk();

        $this->post(route('updatePassword'), [
            'token' => $token,
            'password' => 'StrongPass1!',
            'password_confirmation' => 'StrongPass1!',
        ])->assertRedirect(route('login'));

        $member->refresh();
        $this->assertTrue(Hash::check('StrongPass1!', $member->password));
        $this->assertNull($member->forgot_token);
        $this->assertNull($member->expires_at);
    }

    private function createProgress(User $member, string $date): Progress
    {
        return Progress::create([
            'user_id' => $member->id,
            'avg_kcal_burned' => 0,
            'current_weight' => '70',
            'workout_on' => $date,
        ]);
    }

    private function createWorkout(): Workout
    {
        return Workout::create([
            'workout_name' => 'Push Ups',
            'body_part' => 'Chest',
            'kcal_per_minute' => 8,
            'image' => 'workouts/push-ups.jpg',
        ]);
    }

    private function workoutPayload(Workout $workout, string $date): array
    {
        return [
            'weight' => 70,
            'workout_on' => $date,
            'workouts_completed' => [
                [
                    'workout_id' => $workout->id,
                    'sets' => 3,
                    'reps' => 10,
                    'weight' => 20,
                ],
            ],
        ];
    }
}
