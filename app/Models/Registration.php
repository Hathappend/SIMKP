<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'full_name', 'nim', 'study_program', 'university', 'email',
        'start_date', 'end_date', 'internship_letter', 'kesbangpol_letter', 'rejection_note',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }
}
