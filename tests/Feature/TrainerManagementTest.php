<?php

namespace Tests\Feature;

use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_remove_trainers(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 1]);

        $this->actingAs($admin)->post(route('admin.trainers.store'), [
            'name' => 'Taylor Coach',
            'email' => 'taylor@example.com',
            'phone' => '555-0101',
            'specialty' => 'Strength training',
            'experience_years' => 8,
            'bio' => 'Helps members build strength.',
            'photo' => UploadedFile::fake()->image('taylor.jpg'),
            'password' => 'trainer-password',
            'is_active' => 1,
        ])->assertRedirect(route('admin.trainers.index'));

        $trainer = Trainer::where('email', 'taylor@example.com')->firstOrFail();
        Storage::disk('public')->assertExists($trainer->photo);
        $this->assertTrue(Hash::check('trainer-password', $trainer->password));

        $this->actingAs($admin)
            ->get(route('admin.trainers.index'))
            ->assertOk()
            ->assertSee('Trainer directory')
            ->assertSee('Taylor Coach');

        $this->put(route('admin.trainers.update', $trainer), [
            'name' => 'Taylor Updated',
            'email' => 'taylor@example.com',
            'phone' => '555-0102',
            'specialty' => 'Mobility',
            'experience_years' => 9,
            'bio' => 'Updated coaching bio.',
            'is_active' => 1,
        ])->assertRedirect(route('admin.trainers.index'));

        $this->assertDatabaseHas('trainers', [
            'id' => $trainer->id,
            'name' => 'Taylor Updated',
            'specialty' => 'Mobility',
            'experience_years' => 9,
        ]);
        Storage::disk('public')->assertExists($trainer->fresh()->photo);

        $this->delete(route('admin.trainers.destroy', $trainer))
            ->assertRedirect(route('admin.trainers.index'));
        $this->assertDatabaseMissing('trainers', ['id' => $trainer->id]);
        Storage::disk('public')->assertMissing($trainer->photo);
    }

    public function test_admin_can_assign_a_trainer_to_an_active_non_basic_member_and_member_sees_profile(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $plan = $this->createPlan('Premium');
        $member = $this->createActiveMember($plan);
        $trainer = Trainer::create([
            'name' => 'Jordan Trainer',
            'email' => 'jordan@example.com',
            'phone' => '555-0199',
            'specialty' => 'Strength coaching',
            'experience_years' => 6,
            'bio' => 'Focused on safe strength progress.',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.user-management.trainer', $member), ['trainer_id' => $trainer->id])
            ->assertRedirect(route('admin.user-management.show', $member))
            ->assertSessionHas('success', 'Trainer assigned successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'trainer_id' => $trainer->id,
        ]);

        $this->actingAs($member)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Your personal trainer')
            ->assertSee('Jordan Trainer')
            ->assertSee('jordan@example.com')
            ->assertSee('555-0199')
            ->assertSee('Focused on safe strength progress.');
    }

    public function test_basic_unpaid_and_expired_members_cannot_be_assigned_a_trainer(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $trainer = Trainer::create([
            'name' => 'Morgan Coach',
            'specialty' => 'Conditioning',
            'experience_years' => 4,
        ]);

        $basicMember = $this->createActiveMember($this->createPlan('Basic'));
        $unpaidMember = $this->createActiveMember($this->createPlan('Premium Unpaid'), 'pending');
        $expiredMember = $this->createActiveMember($this->createPlan('Premium Expired'));
        $expiredMember->membership_expires_at = now()->subDay();
        $expiredMember->save();

        foreach ([$basicMember, $unpaidMember, $expiredMember] as $member) {
            $this->actingAs($admin)
                ->patch(route('admin.user-management.trainer', $member), ['trainer_id' => $trainer->id])
                ->assertSessionHasErrors('trainer_id');

            $this->assertDatabaseHas('users', [
                'id' => $member->id,
                'trainer_id' => null,
            ]);
        }
    }

    public function test_removing_a_trainer_clears_the_member_assignment(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $trainer = Trainer::create([
            'name' => 'Casey Coach',
            'specialty' => 'Mobility',
            'experience_years' => 3,
        ]);
        $member = User::factory()->create(['role' => 0, 'trainer_id' => $trainer->id]);

        $this->actingAs($admin)
            ->delete(route('admin.trainers.destroy', $trainer))
            ->assertRedirect(route('admin.trainers.index'));

        $this->assertDatabaseHas('users', ['id' => $member->id, 'trainer_id' => null]);
    }

    public function test_trainer_can_sign_in_and_manage_only_assigned_members(): void
    {
        $plan = $this->createPlan('Premium Coach Access');
        $member = $this->createActiveMember($plan);
        $unassignedMember = User::factory()->create(['role' => 0, 'email' => 'unassigned@example.com']);
        $trainer = Trainer::create([
            'name' => 'Jordan Trainer',
            'email' => 'jordan.login@example.com',
            'password' => 'trainer-secret',
            'specialty' => 'Strength training',
            'experience_years' => 7,
        ]);
        $member->trainer_id = $trainer->id;
        $member->save();

        $this->get(route('trainer.dashboard'))->assertRedirect(route('trainer.login'));
        $this->post(route('trainer.login.submit'), [
            'email' => $trainer->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post(route('trainer.login.submit'), [
            'email' => $trainer->email,
            'password' => 'trainer-secret',
            'remember' => true,
        ])->assertRedirect(route('trainer.dashboard'));
        $this->assertNotEmpty($trainer->fresh()->remember_token);

        $this->get(route('trainer.dashboard'))
            ->assertOk()
            ->assertSee($member->email)
            ->assertDontSee($unassignedMember->email);

        $this->get(route('trainer.members.show', $unassignedMember))->assertNotFound();

        $this->post(route('trainer.members.daily-plan', $member), [
            'plan_date' => today()->toDateString(),
            'daily_instruction' => 'Warm up, then complete three sets of squats.',
            'diet_plan' => 'Include protein with lunch and drink water throughout the day.',
            'progress_feedback' => 'Your consistency is improving. Keep recording each session.',
        ])->assertRedirect(route('trainer.members.show', [
            'user' => $member,
            'edit_plan' => today()->toDateString(),
        ]));

        $this->assertDatabaseHas('trainer_daily_plans', [
            'trainer_id' => $trainer->id,
            'user_id' => $member->id,
            'daily_instruction' => 'Warm up, then complete three sets of squats.',
        ]);

        $this->patch(route('trainer.password.update'), [
            'current_password' => 'trainer-secret',
            'password' => 'new-trainer-secret',
            'password_confirmation' => 'new-trainer-secret',
        ])->assertRedirect(route('trainer.dashboard'))
            ->assertSessionHas('success', 'Your password has been updated.');
        $this->assertTrue(Hash::check('new-trainer-secret', $trainer->fresh()->password));

        $this->actingAs($member)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Today’s coaching')
            ->assertSee('Warm up, then complete three sets of squats.')
            ->assertSee('Include protein with lunch and drink water throughout the day.')
            ->assertSee('Your consistency is improving. Keep recording each session.');
    }

    public function test_inactive_trainer_cannot_sign_in(): void
    {
        $trainer = Trainer::create([
            'name' => 'Inactive Trainer',
            'email' => 'inactive-trainer@example.com',
            'password' => 'trainer-secret',
            'specialty' => 'Mobility',
            'experience_years' => 3,
            'is_active' => false,
        ]);

        $this->post(route('trainer.login.submit'), [
            'email' => $trainer->email,
            'password' => 'trainer-secret',
        ])->assertSessionHasErrors('email');
    }

    private function createPlan(string $name): MembershipPlan
    {
        return MembershipPlan::create([
            'name' => $name,
            'price' => 1000,
            'duration_months' => 1,
            'is_active' => true,
        ]);
    }

    private function createActiveMember(MembershipPlan $plan, string $paymentStatus = 'received'): User
    {
        $member = User::factory()->create([
            'role' => 0,
            'status' => true,
            'membership_plan_id' => $plan->id,
            'cash_payment_status' => $paymentStatus,
            'membership_started_at' => now()->subDay(),
            'membership_expires_at' => now()->addDays(29),
        ]);

        return $member;
    }
}
