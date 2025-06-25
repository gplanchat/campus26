<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Payment;

use ApiPlatform\Metadata\IriConverterInterface;
use App\Authentication\Domain\User\UserId;
use App\Charging\Domain\Payment\PaymentId;
use App\Charging\Domain\Payment\Query\Payment;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PaymentIdNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(
        private IriConverterInterface $iriConverter,
    ) {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!$data instanceof PaymentId) {
            throw new InvalidArgumentException('The provided data type is not supported for normalization.');
        }

        if (!\array_key_exists('iri_only', $context) || false === $context['iri_only']) {
            return $data->toString();
        }

        return $this->iriConverter->getIriFromResource(Payment::class, context: [
            'uri_variables' => [
                'id' => $data->toString(),
            ],
        ]) ?: throw new InvalidArgumentException('The provided data type is not supported for normalization.');
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PaymentId;
    }

    public function getSupportedTypes(?string $format): array
    {
        return \in_array($format, ['json', 'jsonld'], true) ? [
            'object' => false,
        ] : [];
    }
    /**
     * @param class-string<PaymentId> $type
     * @param array{}              $context
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = []): PaymentId
    {
        if (!\is_string($data)
            || \strlen($data) <= 0
            || !class_exists($type)
            || !is_a($type, PaymentId::class, true)
        ) {
            throw new InvalidArgumentException('The provided data type is not supported for denormalization');
        }

        if (!\array_key_exists('iri_only', $context) || false === $context['iri_only']) {
            return $type::fromString($data);
        }

        return $type::fromUri($data);
    }

    /**
     * @param array{} $context
     */
    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = []): bool
    {
        return \is_string($data)
            && is_a($type, PaymentId::class, true);
    }
}
