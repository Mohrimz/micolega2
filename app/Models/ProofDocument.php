<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProofDocument extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'skill_id',
    'document_path',
    'status',
    'skill_request_id',
    'notes', 
    'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
    public function skillRequest()
{
    return $this->belongsTo(SkillRequest::class);
}


  
   


}
