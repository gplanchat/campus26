<?php

namespace Api\Charging;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class SessionsTest extends ApiTestCase
{
    public function testGetOneSession(): void
    {
        static::createClient()->request('GET', '/charging/session/123456-1234-1234-123456789012', [
            'json' => [
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/contexts/Session',
            '@type' => 'Session',
            '@id' => '/charging/session/123456-1234-1234-123456789012',
            'id' => '123456-1234-1234-123456789012'
        ]);
    }

    public function testGetSeveralSessions(): void
    {
        static::createClient()->request('GET', '/charging/session', [
            'json' => [
            ],
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

    public function testCreateSession(): void
    {
        static::createClient()->request('POST', '/charging/session', [
            'json' => [
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
