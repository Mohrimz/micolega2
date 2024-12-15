<?php

use App\Models\User;
use App\Models\SessionRequest;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class RequestSessionTest extends TestCase
{
    public function testUserCanRequestSession()
    {
        // Manually create a user
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',  // Add this field
            'email' => 'john@students.apiit.lk',
            'password' => Hash::make('password'),  // Make sure to hash the password
            'phone_number' => '1234567890',
            'address' => '123 Main St',
            'RoleID' => 3,  
        ]);

        // Ensure the user is created successfully
        $this->assertDatabaseHas('users', [
            'email' => 'john@students.apiit.lk',
        ]);

        // Send the session request
        $response = $this->actingAs($user)->post(route('tutors.requestSession'), [
            'skill_id' => 2,  
            'availability_id' => 2,  
        ]);

        // Assert that the response is successful
        $response->assertStatus(200);

        // Check if the session request was added to the database
        $this->assertDatabaseHas('session_requests', [
            'user_id' => $user->id,
            'skill_id' => 2,
        ]);
    }
}
