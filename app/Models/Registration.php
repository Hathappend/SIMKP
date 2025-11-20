<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'student_id',
        'division_id',
        'mentor_id',
        'start_date',
        'end_date',
        'internship_letter',
        'kesbangpol_letter',
        'application_status',
        'letter_status',
        'rejection_note',
        'letter_number',
        'letter_date',
        'reply_letter_path',
        'report_file',
        'report_status',
        'report_feedback',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(RegistrationMember::class);
    }
}
