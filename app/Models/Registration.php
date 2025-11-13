<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'full_name', 'nim', 'study_program', 'email',
        'start_date', 'end_date', 'internship_letter', 'kesbangpol_letter'
    ];
}
