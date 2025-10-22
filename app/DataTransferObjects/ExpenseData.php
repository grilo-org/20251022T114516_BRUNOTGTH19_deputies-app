<?php

namespace App\DataTransferObjects;

class ExpenseData
{
    public function __construct(
        public readonly ?int $external_id,
        public readonly int $deputy_external_id,
        public readonly float $value,
        public readonly string $date,
        public readonly string $description,
        public readonly ?string $document_url,
        public readonly ?string $supplier_name,
        public readonly ?string $supplier_document,
        public readonly string $expense_type,
        public readonly int $year,
        public readonly int $month
    ) {}
}