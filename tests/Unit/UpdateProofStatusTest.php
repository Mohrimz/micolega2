<?php

use App\Models\User;
use App\Models\ProofDocument;

it('updates the proof document status', function () {
    $user = User::factory()->create();
    $proofDocument = ProofDocument::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(route('teach.updateProofStatus', $proofDocument), [
        'status' => 'approved'
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('proof_documents', [
        'id' => $proofDocument->id,
        'status' => 'approved'
    ]);
});
