<?php

namespace App\Application\RevolutApi\Auth;
class RevolutAuth
{
    public function __construct(private readonly string $bearerToken)
    {
    }

    public function getPlainBearerToken(): string
    {
        return $this->bearerToken;
    }
}