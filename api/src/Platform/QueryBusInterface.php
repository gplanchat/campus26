<?php

declare(strict_types=1);

namespace App\Platform;

interface QueryBusInterface
{
    public function query(object $query): object;
}
