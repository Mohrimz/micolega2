<?php
// tests/Feature/RegisterControllerTest.php

use App\Models\Role;
use App\Models\User;
use App\Models\Skill;
use App\Models\Availability;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
it('registers a user successfully', function () {
    // Create roles, skills, and availabilities for the test
    $role = Role::factory()->create(['RoleName' => 'peer-student']);
    $skill = Skill::factory()->create();
    $availability = Availability::factory()->create([
        'date' => '2024-11-30',  // Example date
        'time' => '08:00:00',    // Example time
    ]);

    // Prepare the registration data
    $data = [
        'name' => 'Nova Pluto',
        'email' => 'CB098765@students.apiit.lk',
        'password' => 'Pluto123',
        'password_confirmation' => 'Pluto123',
        'level' => 'L4',
        'skills' => [$skill->id],
        'availabilities' => [$availability->id],
    ];

    // Send the registration request
    $response = $this->post('/register', $data);

    // Assert that the user was created
    $user = User::where('email', $data['email'])->first();
    expect($user)->not()->toBeNull();
    expect($user->name)->toBe($data['name']);
    expect(Hash::check($data['password'], $user->password))->toBeTrue();
    expect($user->level)->toBe($data['level']);

    // Assert that the user has the default role
    $this->assertTrue($user->roles->contains('RoleName', 'peer-student'));

    // Assert that the user has the selected skill and availability
    $this->assertTrue($user->skills->contains('id', $skill->id));
    $this->assertTrue($user->availabilities->contains('id', $availability->id));

    // Assert that the response redirects to the dashboard
    $response->assertRedirect(route('dashboard'));
});
