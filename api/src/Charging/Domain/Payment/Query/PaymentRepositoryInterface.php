<?php

namespace App\Charging\Domain\Payment\Query;

use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Payment\PaymentId;

interface PaymentRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(PaymentId $id): Payment;
}
