<?php

use App\Models\Category;
use App\Models\Skill;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\ProofDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\{
    actingAs,
    get,
    post,
    put,
    delete,
    assertDatabaseHas,
    assertDatabaseMissing
};

beforeEach(function () {
    // Ensure the admin role exists
    $adminRole = Role::factory()->create([
        'RoleID' => 1, // Assuming 'RoleID' is a column in your 'roles' table
        'RoleName' => 'admin', // Provide appropriate values
    ]);

    // Create a user with the admin role
    $this->adminUser = User::factory()->create();
    UserRole::create([
        'user_id' => $this->adminUser->id,
        'role_id' => $adminRole->RoleID,
    ]);

    actingAs($this->adminUser);
});

it('shows the dashboard with categories, skills, and proof documents', function () {
    Category::factory(3)->create();
    Skill::factory(5)->create();
    ProofDocument::factory(2)->create();

    $response = get('/admin/dashboard');

    $response->assertOk();
    $response->assertViewHasAll(['categories', 'skills', 'proofDocuments']);
});

it('updates the status of a proof document', function () {
    $proofDocument = ProofDocument::factory()->create(['status' => 'pending']);

    put("/admin/proof-documents/{$proofDocument->id}/status", [
        'status' => 'approved',
    ])->assertRedirect();

    assertDatabaseHas('proof_documents', [
        'id' => $proofDocument->id,
        'status' => 'approved',
    ]);
});

it('adds a peer-tutor role to a user with approved proof documents', function () {
    $peerTutorRole = Role::where('RoleName', 'peer-tutor')->first();
    $user = User::factory()->create();

    $proofDocument = ProofDocument::factory()->create([
        'user_id' => $user->id,
        'status' => 'approved',
    ]);

    put("/admin/proof-documents/{$proofDocument->id}/status", [
        'status' => 'approved',
    ])->assertRedirect();

    assertDatabaseHas('user_roles', [
        'user_id' => $user->id,
        'role_id' => $peerTutorRole->RoleID,
    ]);
});

it('rejects a proof document with a reason', function () {
    $proofDocument = ProofDocument::factory()->create(['status' => 'pending']);

    post('/admin/proof-documents/reject', [
        'document_id' => $proofDocument->id,
        'rejection_reason' => 'Not valid proof.',
    ])->assertRedirect();

    assertDatabaseHas('proof_documents', [
        'id' => $proofDocument->id,
        'status' => 'rejected',
        'rejection_reason' => 'Not valid proof.',
    ]);
});

it('approves a proof document with notes', function () {
    $proofDocument = ProofDocument::factory()->create(['status' => 'pending']);

    post('/admin/proof-documents/accept', [
        'document_id' => $proofDocument->id,
        'accept_notes' => 'Great submission!',
    ])->assertRedirect();

    assertDatabaseHas('proof_documents', [
        'id' => $proofDocument->id,
        'status' => 'approved',
        'notes' => 'Great submission!',
    ]);
});

it('adds a new skill', function () {
    $category = Category::factory()->create();

    post('/admin/skills', [
        'name' => 'New Skill',
        'description' => 'Skill description.',
        'category_id' => $category->id,
    ])->assertRedirect();

    assertDatabaseHas('skills', [
        'name' => 'New Skill',
        'description' => 'Skill description.',
        'category_id' => $category->id,
    ]);
});

it('deletes a skill and its relations', function () {
    $skill = Skill::factory()->create();
    $skill->users()->attach(User::factory(3)->create());

    delete("/admin/skills/{$skill->id}")->assertRedirect();

    assertDatabaseMissing('skills', [
        'id' => $skill->id,
    ]);
});
