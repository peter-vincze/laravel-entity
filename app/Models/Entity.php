<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
    	'salary',
        'name',
        'email',
        'docker',
        'agile',
        'start',
        'senior',
        'fullstack',
        'description',
    ];

}
