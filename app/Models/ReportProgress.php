<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportProgress extends Model
{
    protected $fillable = [
        'student_id',
        'stage_key',
        'stage_label',
        'status',
        'file_path',
        'feedback'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'green',
            'revision' => 'orange',
            'submitted' => 'blue',
            'ongoing' => 'indigo',
            default => 'gray',
        };
    }
}
