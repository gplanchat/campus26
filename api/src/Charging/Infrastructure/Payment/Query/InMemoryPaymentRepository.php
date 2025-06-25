<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Payment\Query;

use App\Charging\Domain\NotFoundException;
use App\Charging\Domain\Payment\Query\Payment;
use App\Charging\Domain\Payment\Query\PaymentRepositoryInterface;
use App\Charging\Domain\Payment\PaymentId;
use App\Platform\Collection\Collection;

final class InMemoryPaymentRepository implements PaymentRepositoryInterface
{
    /**
     * @param Payment[] $payments
     */
    public function __construct(
        private array $payments = []
    ) {
    }

    public static function withTestingFixtures(): self
    {
        return new self([
            new Payment(PaymentId::fromString('0197a142-47ee-76c5-bbc9-0b290166e1fa')),
            new Payment(PaymentId::fromString('0197a142-47ee-7041-97a2-0bc76b1c0c73')),
            new Payment(PaymentId::fromString('0197a142-47ee-79c4-8fa4-8b00729db8f5')),
            new Payment(PaymentId::fromString('0197a142-47ee-7b5d-9c20-ef27227a3a7e')),
            new Payment(PaymentId::fromString('0197a142-47ee-7cc8-ad1a-26b4d8178504')),
            new Payment(PaymentId::fromString('0197a142-47ee-7809-8797-ed99fd43958d')),
            new Payment(PaymentId::fromString('0197a142-47ee-7144-925f-e07f83a4a2b8')),
            new Payment(PaymentId::fromString('0197a142-47ef-7023-9039-42c09d7aef83')),
            new Payment(PaymentId::fromString('0197a142-47ef-79ef-9287-a29ec8bebea0')),
            new Payment(PaymentId::fromString('0197a142-47ef-7908-8978-2f82003ad410')),
        ]);
    }

    public function get(PaymentId $id): Payment
    {
        $result = Collection::fromArray($this->payments)
            ->filter(fn (Payment $current) => $current->id->equals($id))
            ->limit(1)
            ->toArray();

        if (count($result) <= 0) {
            throw new NotFoundException();
        }

        return array_shift($result);
    }
}
