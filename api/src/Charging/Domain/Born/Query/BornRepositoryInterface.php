<?php

namespace App\Charging\Domain\Born\Query;

use App\Charging\Domain\Born\BornId;
use App\Charging\Domain\NotFoundException;

interface BornRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(BornId $id): Born;
}
