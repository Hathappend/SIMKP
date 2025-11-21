<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RegistrationMember extends Model
{
    protected $fillable = ['registration_id', 'name', 'nim', 'study_program'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function assessment(): HasOne
    {
        return $this->hasOne(Assessment::class, 'registration_member_id');
    }
}
