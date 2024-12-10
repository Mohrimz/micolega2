<?php

use App\Models\User;
use App\Models\Skill;
use App\Models\Category;
use App\Models\ProofDocument;
use App\Models\SessionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads the dashboard with the correct data', function () {
    // Arrange: Set up data
    $admin = User::factory()->create(['RoleID' => 1]); // Assuming 1 is the admin role
    $skill = Skill::factory()->create();
    $category = Category::factory()->create();
    $proofDocument = ProofDocument::factory()->create(['skill_id' => $skill->id, 'user_id' => $admin->id, 'status' => 'pending']);
    $sessionRequest = SessionRequest::factory()->create(['user_id' => $admin->id, 'status' => 'accepted']);

    // Act: Authenticate and visit the dashboard
    $this->actingAs($admin)
         ->get(route('dashboard'))
         ->assertOk() // Ensure the page loads successfully
         ->assertViewHasAll([
             'proofDocuments' => function ($proofDocuments) use ($proofDocument) {
                 return $proofDocuments->contains($proofDocument);
             },
             'categories' => function ($categories) use ($category) {
                 return $categories->contains($category);
             },
             'skills' => function ($skills) use ($skill) {
                 return $skills->contains($skill);
             },
             'tutors',
             'Alltutors',
         ]);
});

it('filters skills by the provided filter_skill query', function () {
    // Arrange: Set up data
    $user = User::factory()->create(['RoleID' => 2]); // Assuming 2 is a general user role
    $skill = Skill::factory()->create(['name' => 'Laravel']);
    Skill::factory()->create(['name' => 'React']); // Unrelated skill

    // Act: Authenticate and apply a filter
    $response = $this->actingAs($user)
                     ->get(route('dashboard', ['filter_skill' => 'Laravel']));

    // Assert: Ensure the filtered skill appears in the view
    $response->assertOk()
             ->assertViewHas('skills', function ($skills) use ($skill) {
                 return $skills->contains($skill);
             });
});
