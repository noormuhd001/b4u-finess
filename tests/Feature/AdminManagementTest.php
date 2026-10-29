<?php

namespace Tests\Feature;

use App\Mail\Auth\RegistrationOtpMail;
use App\Models\AttendanceRecord;
use App\Models\Badges;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Models\Workout;
use Database\Seeders\MembershipPlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

        $user = User::factory()->create(['role' => 0]);
        $this->actingAs($admin)
            ->get(route('admin.user-management.index'))
            ->assertOk()
            ->assertSee('User management')
            ->assertSee($user->email)
            ->assertDontSee($admin->email)
            ->assertDontSee('Members');

        $this->actingAs($admin)->get(route('admin.workouts.index'))->assertOk()->assertSee('Workout library');
        $this->actingAs($admin)->get(route('admin.badges.index'))
            ->assertOk()
            ->assertSee('Badge catalog')
            ->assertDontSee('Create badge')
            ->assertDontSee('Awarded to');
        $this->get('/admin/progress/reviews')->assertNotFound();

        $this->actingAs($admin)
            ->get(route('admin.user-management.show', $user))
            ->assertOk()
            ->assertSee('Progress history');
    }

    public function test_admin_can_only_edit_badge_name_and_logo_without_changing_awards(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $member = User::factory()->create(['role' => 0]);
        $badge = Badges::create([
            'name' => 'First Workout',
            'icon' => 'img/first-workout.png',
            'description' => 'Complete your first workout.',
        ]);
        $badge->users()->attach($member);

        $this->actingAs($admin)
            ->get(route('admin.badges.index'))
            ->assertOk()
            ->assertDontSee($member->email);

        $this->actingAs($admin)->post('/admin/badges')->assertNotFound();

        $this->actingAs($admin)
            ->put(route('admin.badges.update', $badge), [
                'name' => 'Workout Starter',
                'description' => 'Changed through an unsupported field.',
                'users' => [],
            ])
            ->assertRedirect(route('admin.badges.index'));

        $this->assertDatabaseHas('badges', [
            'id' => $badge->id,
            'name' => 'Workout Starter',
            'icon' => 'img/first-workout.png',
            'description' => 'Complete your first workout.',
        ]);
        $this->assertDatabaseHas('badge_users', [
            'badge_id' => $badge->id,
            'user_id' => $member->id,
        ]);
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

    public function test_registration_requires_a_plan_and_account_activates_only_after_email_otp_verification(): void
    {
        Mail::fake();
        $plan = MembershipPlan::create([
            'name' => 'Basic',
            'price' => 500,
            'duration_months' => 1,
            'is_active' => true,
        ]);

        $this->get(route('signUp'))->assertOk()->assertSee('Choose your membership');

        $this->post(route('register'), [
            'username' => 'New Member',
            'email' => 'registered@example.com',
            'password' => 'StrongPass1!',
            'confirm_password' => 'StrongPass1!',
        ])->assertSessionHasErrors('membership_plan_id');

        $this->post(route('register'), [
            'username' => 'New Member',
            'email' => 'registered@example.com',
            'password' => 'StrongPass1!',
            'confirm_password' => 'StrongPass1!',
            'membership_plan_id' => $plan->id,
        ])->assertRedirect(route('registration.verify', ['email' => 'registered@example.com']))
            ->assertSessionHas('otp_sent', true);

        $this->assertDatabaseHas('users', [
            'email' => 'registered@example.com',
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => 'pending',
            'status' => false,
        ]);

        $otp = null;
        Mail::assertSent(RegistrationOtpMail::class, function (RegistrationOtpMail $mail) use (&$otp) {
            $otp = $mail->otp;

            return $mail->hasTo('registered@example.com');
        });

        $this->post(route('registration.verify.submit'), [
            'email' => 'registered@example.com',
            'otp' => '000000',
        ])->assertSessionHasErrors('otp');

        $this->assertDatabaseHas('users', [
            'email' => 'registered@example.com',
            'status' => false,
        ]);

        $this->post(route('registration.verify.submit'), [
            'email' => 'registered@example.com',
            'otp' => $otp,
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'registered@example.com',
            'status' => true,
        ]);
    }

    public function test_member_can_see_membership_plan_on_profile(): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Basic',
            'price' => 500,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $member = User::factory()->create([
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => 'pending',
        ]);

        $this->actingAs($member)
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Membership plan')
            ->assertSee('Basic')
            ->assertSee('Pending');
    }

    public function test_admin_can_manage_membership_plans_and_cash_payment_status(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $plan = MembershipPlan::create([
            'name' => 'Basic',
            'price' => 500,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $member = User::factory()->create([
            'role' => 0,
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.membership-plans.index'))
            ->assertOk()
            ->assertSee('Membership plans')
            ->assertSee('Basic');

        $this->actingAs($admin)->post(route('admin.membership-plans.store'), [
            'name' => 'Premium',
            'price' => 2000,
            'duration_months' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('admin.membership-plans.index'));

        $this->actingAs($admin)->patch(route('admin.user-management.payment', $member), [
            'cash_payment_status' => 'received',
        ])->assertRedirect(route('admin.user-management.index'));

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'cash_payment_status' => 'received',
        ]);
        $this->assertNotNull($member->fresh()->membership_started_at);
        $this->assertTrue($member->fresh()->membership_expires_at->isFuture());
        $this->assertDatabaseHas('membership_plans', [
            'name' => 'Premium',
            'price' => 2000,
        ]);
    }

    public function test_membership_plan_seeder_creates_the_three_default_monthly_plans(): void
    {
        $this->seed(MembershipPlanSeeder::class);

        $this->assertDatabaseCount('membership_plans', 3);
        $this->assertDatabaseHas('membership_plans', [
            'name' => 'Basic',
            'price' => 500,
            'duration_months' => 1,
        ]);
        $this->assertDatabaseHas('membership_plans', [
            'name' => 'Regular with PT',
            'price' => 1000,
            'duration_months' => 1,
        ]);
        $this->assertDatabaseHas('membership_plans', [
            'name' => 'Premium',
            'price' => 2000,
            'duration_months' => 1,
        ]);
    }

    public function test_active_paid_member_can_check_in_only_once_per_day(): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Monthly',
            'price' => 500,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $member = User::factory()->create([
            'role' => 0,
            'status' => true,
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => 'received',
        ]);
        $member->membership_started_at = now()->subDays(2);
        $member->membership_expires_at = now()->addDays(28);
        $member->save();

        $this->actingAs($member)
            ->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('Check in now');

        $this->post(route('attendance.check-in'))->assertRedirect(route('attendance.index'));
        $this->assertDatabaseCount('attendance_records', 1);
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $member->id,
            'check_in_date' => today()->toDateString(),
        ]);

        $this->post(route('attendance.check-in'))
            ->assertRedirect(route('attendance.index'))
            ->assertSessionHas('error', 'You have already checked in today.');
        $this->assertDatabaseCount('attendance_records', 1);
    }

    public function test_expired_or_unpaid_member_cannot_check_in(): void
    {
        $plan = MembershipPlan::create([
            'name' => 'Monthly',
            'price' => 500,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $member = User::factory()->create([
            'role' => 0,
            'status' => true,
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => 'received',
        ]);
        $member->membership_started_at = now()->subMonths(2);
        $member->membership_expires_at = now()->subMonth();
        $member->save();

        $this->actingAs($member)
            ->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('Membership expired')
            ->assertDontSee('Check in now');

        $this->post(route('attendance.check-in'))
            ->assertRedirect(route('attendance.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('attendance_records', 0);

        $member->update(['cash_payment_status' => 'pending']);
        $this->assertFalse($member->fresh()->hasActiveMembership());
    }

    public function test_admin_can_view_attendance_reports_and_inactive_members(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $plan = MembershipPlan::create([
            'name' => 'Monthly',
            'price' => 500,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $member = User::factory()->create([
            'role' => 0,
            'status' => true,
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => 'received',
        ]);
        $member->membership_started_at = now()->subDays(40);
        $member->membership_expires_at = now()->addDays(20);
        $member->save();
        AttendanceRecord::create([
            'user_id' => $member->id,
            'check_in_date' => today()->subDays(35),
            'checked_in_at' => now()->subDays(35),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.attendance.index', ['period' => 'monthly']))
            ->assertOk()
            ->assertSee('Check-in history')
            ->assertSee('Members to follow up')
            ->assertSee($member->name);
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
