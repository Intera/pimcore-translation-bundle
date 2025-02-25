<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Tests\DivanteTranslationBundle\Provider;

use DivanteTranslationBundle\Provider\DeeplProvider;
use DivanteTranslationBundle\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;
use Tests\DivanteTranslationBundle\Helper\Builder\TranslationProviderBuilder;

final class DeeplProviderTest extends TestCase
{
    public function testTranslate(): void
    {
        // arrange
        $response = [
            'translations' => [
                [
                    'text' => 'test'
                ],
            ],
        ];

        $provider = $this->createProvider(200, $response);
        $provider->setApiKey('testApiKey');
        $provider->setFormality('default');

        // act
        $actual = $provider->translate('test', 'en');

        // assert
        $this->assertSame('test', $actual);
    }

    /**
     * @return DeeplProvider
     *
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    private function createProvider(int $statusCode, array $response): ProviderInterface
    {
        $builder = new TranslationProviderBuilder('');
        return $builder->createGuzzleClientStub($statusCode, $response)
            ->createHttpClient()
            ->createProvider(DeeplProvider::class);
    }
}
