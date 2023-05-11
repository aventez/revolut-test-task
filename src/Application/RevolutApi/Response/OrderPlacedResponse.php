<?php

namespace App\Application\RevolutApi\Response;

class OrderPlacedResponse
{
    public function __construct(
        public readonly string $id,
        public readonly string $publicId,
        public readonly string $type,
        public readonly string $state
    ) {
    }
}
