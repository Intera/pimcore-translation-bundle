<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Tests\DivanteTranslationBundle\Provider;

use DivanteTranslationBundle\Provider\MicrosoftProvider;
use DivanteTranslationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\DivanteTranslationBundle\Helper\Builder\TranslationProviderBuilder;

final class MicrosoftProviderTest extends TestCase
{
    public function testTranslate(): void
    {
        // arrange
        $response = [
            [
                'translations' => [
                    [
                        'text' => 'test'
                    ],
                ],
            ],
        ];
        $provider = $this->createProvider(200, $response);
        $provider->setApiKey('testApiKry');


        // act
        $actual = $provider->translate('test', 'en');


        // assert
        $this->assertSame('test', $actual);
    }

    /**
     * @return MicrosoftProvider
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    private function createProvider(int $statusCode, array $response): ProviderInterface
    {
        $builder = new TranslationProviderBuilder('');

        return $builder->createGuzzleClientStub($statusCode, $response)
            ->createHttpClient()
            ->createProvider(MicrosoftProvider::class);
    }
}
