<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    protected $fillable = [
        'registration_id',
        'registration_member_id',
        'score_discipline',
        'score_technical',
        'score_performance',
        'score_initiative',
        'score_personality',
        'final_score',
        'grade',
        'notes',
        'certificate_number',
        'certificate_path',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
