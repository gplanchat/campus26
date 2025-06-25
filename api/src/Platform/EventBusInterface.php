<?php

declare(strict_types=1);

namespace App\Platform;

interface EventBusInterface
{
    public function emit(object $event): void;
}
