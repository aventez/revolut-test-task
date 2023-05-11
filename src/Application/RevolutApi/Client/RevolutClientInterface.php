<?php

namespace App\Application\RevolutApi\Client;

use App\Application\RevolutApi\Response\OrderPlacedResponse;

interface RevolutClientInterface
{
    public function createOrder(int $amount, string $currency, string $email): OrderPlacedResponse;
}