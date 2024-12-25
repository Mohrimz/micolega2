<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
  // Define the table's fillable fields for mass assignment
  protected $fillable = ['name'];


  public $timestamps = true;
}
