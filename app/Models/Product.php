<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product'; // Define the table name
    protected $guarded = []; // Guarded properties for mass assignment
}