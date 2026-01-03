<?php

declare(strict_types = 1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Test de cada uno de los métodos del CRUD
 */
class MotoTest extends ApiTestCase
{
    // Para evitar un warning por la versión de PHPUnit
    protected static ?bool $alwaysBootKernel = true;

    /**
     * @throws TransportExceptionInterface
     */
    public function testApiPost(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/motos', [
            'json' => [
                'modelo' => 'Street Triple',
                'cilindrada' => 765,
                'marca' => 'Triumph',
                'tipo' => 'Naked',
                'extras' => ['ABS'],
                'peso' => 166,
                'edicionLimitada' => true
            ]
        ]);

        $this->assertResponseIsSuccessful();

    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testApiGet(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/motos');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testApiPatch(): void
    {
        $client = static::createClient();

        $response = $client->request('POST', '/api/motos', [
            'json' => [
                'modelo' => 'Street Triple',
                'cilindrada' => 765,
                'marca' => 'Triumph',
                'tipo' => 'Naked',
                'extras' => ['ABS'],
                'peso' => 166,
                'edicionLimitada' => true
            ]
        ]);

        $id = $response->toArray()['@id'];

        $client->request('PATCH', $id, [
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
            'json' => [
                'marca' => 'Ducati',
                'modelo' => 'Monster'
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testApiPut(): void
    {
        $client = static::createClient();

        $response = $client->request('POST', '/api/motos', [
            'json' => [
                'modelo' => 'Street Triple',
                'cilindrada' => 765,
                'marca' => 'Triumph',
                'tipo' => 'Naked',
                'extras' => ['ABS'],
                'peso' => 166,
                'edicionLimitada' => true
            ]
        ]);

        $id = $response->toArray()['@id'];

        $client->request('PUT', $id, [
            'json' => [
                'modelo' => 'GS',
                'cilindrada' => 1200,
                'marca' => 'BMW',
                'tipo' => 'Trail',
                'extras' => ['ABS', 'Control traccion'],
                'peso' => 240
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testApiDelete(): void
    {
        $client = static::createClient();

        $response = $client->request('POST', '/api/motos', [
            'json' => [
                'modelo' => 'Street Triple',
                'cilindrada' => 765,
                'marca' => 'Triumph',
                'tipo' => 'Naked',
                'extras' => ['ABS'],
                'peso' => 166,
                'edicionLimitada' => true
            ]
        ]);

        $id = $response->toArray()['@id'];

        $client->request('DELETE', $id);

        $this->assertResponseIsSuccessful();
    }

}
