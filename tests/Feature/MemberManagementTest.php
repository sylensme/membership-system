<?php

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('members can be searched by name category and level', function () {
    Member::factory()->create([
        'name' => 'Ana Reyes',
        'category' => 'Student',
        'level' => 'Gold',
    ]);
    Member::factory()->create([
        'name' => 'Ben Cruz',
        'category' => 'Corporate',
        'level' => 'Basic',
    ]);

    $this->get('/members?name=Ana&category=Student&level=Gold')
        ->assertOk()
        ->assertSee('Ana Reyes')
        ->assertDontSee('Ben Cruz');
});

test('a member can be added and viewed', function () {
    $response = $this->post('/members', [
        'name' => 'Carla Santos',
        'email' => 'carla@example.com',
        'phone' => '09171234567',
        'category' => 'Student',
        'level' => 'Silver',
        'membership_fee' => '750.00',
        'validity_months' => '12',
        'benefits' => 'Library and event access',
        'address' => 'Manila',
    ]);

    $member = Member::where('email', 'carla@example.com')->firstOrFail();

    $response->assertRedirect(route('members.show', $member));
    $this->get(route('members.show', $member))
        ->assertOk()
        ->assertSee('Carla Santos')
        ->assertSee('Silver')
        ->assertSee('750.00')
        ->assertSee('Library and event access');
});

test('a member can be edited', function () {
    $member = Member::factory()->create();

    $this->put(route('members.update', $member), [
        'name' => 'Updated Member',
        'email' => $member->email,
        'phone' => $member->phone,
        'category' => 'Regular',
        'level' => 'Platinum',
        'membership_fee' => '2500.00',
        'validity_months' => '24',
        'benefits' => 'Premium benefits',
        'address' => $member->address,
        'is_active' => '1',
    ])->assertRedirect(route('members.show', $member));

    expect($member->refresh()->name)->toBe('Updated Member')
        ->and($member->category)->toBe('Regular')
        ->and($member->level)->toBe('Platinum')
        ->and($member->membership_fee)->toBe('2500.00')
        ->and($member->validity_months)->toBe(24);
});

test('a member can be deactivated', function () {
    $member = Member::factory()->create(['is_active' => true]);

    $this->patch(route('members.deactivate', $member))
        ->assertRedirect(route('members.index'));

    expect($member->refresh()->is_active)->toBeFalse();
});

test('a member can be deleted', function () {
    $member = Member::factory()->create();

    $this->delete(route('members.destroy', $member))
        ->assertRedirect(route('members.index'));

    $this->assertDatabaseMissing('members', ['id' => $member->id]);
});
