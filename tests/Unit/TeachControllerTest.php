<?php

use App\Models\User;
use App\Models\Skill;
use App\Models\ProofDocument;

it('displays the list of proof documents for the teacher', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $proofDocument = ProofDocument::factory()->create(['user_id' => $user->id, 'skill_id' => $skill->id]);

    $response = $this->actingAs($user)->get(route('teach.index'));

    $response->assertStatus(200);
    $response->assertViewHas('proofDocuments', function ($documents) use ($proofDocument) {
        return $documents->contains($proofDocument);
    });
});
