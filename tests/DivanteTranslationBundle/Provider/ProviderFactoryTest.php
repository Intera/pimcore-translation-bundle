<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Tests\DivanteTranslationBundle\Provider;

use ArrayObject;
use DivanteTranslationBundle\Exception\TranslationProviderNotImplemented;
use DivanteTranslationBundle\Http\HttpClient;
use DivanteTranslationBundle\Provider\DeeplFreeProvider;
use DivanteTranslationBundle\Provider\DeeplProvider;
use DivanteTranslationBundle\Provider\GoogleProvider;
use DivanteTranslationBundle\Provider\MicrosoftProvider;
use DivanteTranslationBundle\Provider\ProviderFactory;
use DivanteTranslationBundle\Provider\ProviderInterface;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

final class ProviderFactoryTest extends TestCase
{
    /**
     * @dataProvider translatorDataProvider
     */
    public function testGet(string $providerName, string $expectedClassname): void
    {
        // arrange
        $factory = new ProviderFactory('test', $this->getProviders(), 'default');

        //act
        $actual = $factory->get($providerName);

        // assert
        $this->assertInstanceOf(ProviderInterface::class, $actual);
        $this->assertInstanceOf($expectedClassname, $actual);
    }

    public function testGetException(): void
    {
        $this->expectException(TranslationProviderNotImplemented::class);

        $factory = new ProviderFactory('test', $this->getProviders(), 'default');
        $factory->get('test');
    }

    /**
     * @return array[array{string: providerName, class-string: expectedClassname}]
     */
    public function translatorDataProvider(): array
    {
        return [
            ['google_translate', GoogleProvider::class],
            ['deepl', DeeplProvider::class],
            ['deepl_free', DeeplFreeProvider::class],
            ['microsoft_translate', MicrosoftProvider::class],
        ];
    }

    private function getProviders(): iterable
    {
        $guzzleClient = $this->createStub(ClientInterface::class);
        $httpClient = new HttpClient($guzzleClient);

        $providers = [
            new GoogleProvider($httpClient),
            new DeeplProvider($httpClient),
            new DeeplFreeProvider($httpClient),
            new MicrosoftProvider($httpClient),
        ];

        $arrayObject = new ArrayObject($providers);
        return $arrayObject->getIterator();
    }

}
