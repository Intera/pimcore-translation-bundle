<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Tests\DivanteTranslationBundle\Provider;

use DivanteTranslationBundle\Exception\TranslationException;
use DivanteTranslationBundle\Provider\GoogleProvider;
use DivanteTranslationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Tests\DivanteTranslationBundle\Helper\Builder\TranslationProviderBuilder;

final class GoogleProviderTest extends TestCase
{
    public function testTranslate(): void
    {
        // arrange
        $response = [
            'data' => [
                'translations' => [
                    [
                        'translatedText' => 'test'
                    ],
                ],
            ],
        ];

        $provider = $this->createProvider(200, $response);
        $provider->setApiKey('testApiKey');


        // act
        $actual = $provider->translate('test', 'en');


        // assert
        $this->assertSame('test', $actual);
    }

    public function testTranslateError(): void
    {
        //arrange
        $response = ['error' => 'error text'];

        $provider = $this->createProvider(200, $response);
        $provider->setApiKey('testApiKey');


        // act and assert
        $this->expectException(TranslationException::class);
        $provider->translate('test_error', 'en');
    }

    /**
     * @return GoogleProvider
     *
     * @throws Exception
     */
    private function createProvider(int $statusCode, array $response): ProviderInterface
    {
        $builder = new TranslationProviderBuilder('');

        return $builder->createGuzzleClientStub($statusCode, $response)
            ->createHttpClient()
            ->createProvider(GoogleProvider::class);
    }
}
