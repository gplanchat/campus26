<?php

namespace App\Charging\Domain\Session\Query;

use App\Charging\Domain\Session\SessionId;

interface SessionRepositoryInterface
{
    public function get(SessionId $id): Session;
    public function list(int $currentPage = 1, int $pageSize = 25): SessionPage;
}
