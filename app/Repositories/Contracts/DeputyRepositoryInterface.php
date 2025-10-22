<?php

namespace App\Repositories\Contracts;

use App\Models\Deputy;
use App\DataTransferObjects\DeputyData;
use App\DataTransferObjects\ExpenseData;

interface DeputyRepositoryInterface
{
    public function findOrCreate(DeputyData $deputyData): Deputy;
    public function syncExpenses(Deputy $deputy, array $expenses): void;
}