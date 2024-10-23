<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidate'; // Define the table name
    protected $guarded = []; // Guarded properties for mass assignment
}