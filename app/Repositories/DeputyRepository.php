<?php

namespace App\Repositories;

use App\Models\Deputy;
use App\Models\Expense;
use App\DataTransferObjects\ExpenseData;
use Illuminate\Support\Facades\DB;

class DeputyRepository
{
    public function syncExpenses(Deputy $deputy, array $expenses): void
    {
        DB::transaction(function () use ($deputy, $expenses) {
            $expenseData = array_map(
                function ($data) use ($deputy) {
                    return [
                        'external_id' => $data->external_id,
                        'deputy_id' => $deputy->id,
                        'value' => $data->value,
                        'date' => $data->date,
                        'description' => $data->description,
                        'document_url' => $data->document_url,
                        'supplier_name' => $data->supplier_name,
                        'supplier_document' => $data->supplier_document,
                        'expense_type' => $data->expense_type,
                        'year' => $data->year,
                        'month' => $data->month,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                },
                $expenses
            );

            Expense::upsert(
                $expenseData,
                ['deputy_id', 'external_id'],
                ['value', 'date', 'description', 'document_url', 'supplier_name', 'supplier_document', 'expense_type', 'year', 'month']
            );
        });
    }
}