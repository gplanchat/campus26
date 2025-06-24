<?php

declare(strict_types=1);

namespace App\Charging\Infrastructure\Session;

use ApiPlatform\Metadata\IriConverterInterface;
use App\Authentication\Domain\Role\Query\Role;
use App\Authentication\Domain\Role\RoleId;
use App\Charging\Domain\Session\Query\Session;
use App\Charging\Domain\Session\SessionId;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SessionIdNormalizer implements NormalizerInterface
{
    public function __construct(
        private IriConverterInterface $iriConverter,
    ) {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!$data instanceof SessionId) {
            throw new InvalidArgumentException('The provided data type is not supported for normalization.');
        }

        if (!\array_key_exists('iri_only', $context) || false === $context['iri_only']) {
            return $data->toString();
        }

        return $this->iriConverter->getIriFromResource(Session::class, context: [
            'uri_variables' => [
                'id' => $data->toString(),
            ],
        ]) ?: throw new InvalidArgumentException('The provided data type is not supported for normalization.');
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof SessionId;
    }

    public function getSupportedTypes(?string $format): array
    {
        return \in_array($format, ['json', 'jsonld'], true) ? [
            'object' => false,
        ] : [];
    }
}
