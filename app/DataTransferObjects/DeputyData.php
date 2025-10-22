<?php

namespace App\DataTransferObjects;

class DeputyData
{
    public function __construct(
        public readonly int $external_id,
        public readonly string $name,
        public readonly string $party,
        public readonly string $state,
        public readonly string $photo_url,
        public readonly ?string $email,
        public readonly int $legislature_id
    ) {}
}