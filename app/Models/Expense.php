<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'external_id',
        'deputy_id',
        'value',
        'date',
        'description',
        'document_url',
        'supplier_name',
        'supplier_document',
        'expense_type',
        'year',
        'month'
    ];

    public function deputy(): BelongsTo
    {
        return $this->belongsTo(Deputy::class);
    }
}