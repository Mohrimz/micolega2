<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSessionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'member_emails',
    ];
}