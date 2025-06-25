<?php

namespace Api\Charging;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Charging\Infrastructure\Session\DataFixtures\SessionDataFixtures;
use App\Charging\Infrastructure\StorageMock;
use Psr\Clock\ClockInterface;

class SessionsTest extends ApiTestCase
{
    private ?SessionDataFixtures $fixtures = null;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->fixtures = new SessionDataFixtures($this->getContainer()->get(StorageMock::class));

        $this->fixtures->load($this->getContainer()->get(ClockInterface::class));
    }

    public function tearDown(): void
    {
        $this->fixtures->unload();

        parent::tearDown();
    }

    public function testGetOneSession(): void
    {
        static::createClient()->request('GET', '/charging/session/0197a118-e6af-7bb8-81b0-31b2349f6abe', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/contexts/Session',
            '@type' => 'Session',
            '@id' => '/charging/session/0197a118-e6af-7bb8-81b0-31b2349f6abe',
            'id' => '0197a118-e6af-7bb8-81b0-31b2349f6abe',
        ]);
    }

    public function testGetSeveralSessions(): void
    {
        static::createClient()->request('GET', '/charging/session', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/contexts/Session',
            '@type' => 'Collection',
            '@id' => '/charging/session',
        ]);
    }

    public function testInitializeSession(): void
    {
        static::createClient()->request('POST', '/charging/session', [
            'json' => [
                'paymentId' => '/charging/payment/0197a142-47ee-76c5-bbc9-0b290166e1fa',
                'bornId' => '/charging/born/0197a144-0062-73ab-9b14-a8fdf3026852',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/contexts/Session',
            '@type' => 'Session',
        ]);
    }
}
