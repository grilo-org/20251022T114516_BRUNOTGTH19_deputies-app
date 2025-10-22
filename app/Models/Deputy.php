<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deputy extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'party',
        'state',
        'photo_url',
        'email',
        'legislature_id'
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}