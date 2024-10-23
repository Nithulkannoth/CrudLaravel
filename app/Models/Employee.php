<?php
    
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee'; // Define the table name
    protected $guarded = []; // Guarded properties for mass assignment
}