<?php

namespace App\Event;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

class OnOrderPlacedEvent extends Event
{
    public const NAME = 'order.placed';

    public function __construct(public readonly Uuid $id)
    {
    }
}
