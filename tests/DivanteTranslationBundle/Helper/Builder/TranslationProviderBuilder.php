<?php

declare(strict_types=1);

namespace Tests\DivanteTranslationBundle\Helper\Builder;

use DivanteTranslationBundle\Http\HttpClient;
use DivanteTranslationBundle\Http\HttpClientInterface;
use DivanteTranslationBundle\Provider\AbstractProvider;
use DivanteTranslationBundle\Provider\ProviderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class TranslationProviderBuilder extends TestCase
{
    private ClientInterface $guzzleClient;
    private HttpClientInterface $httpClient;

    /**
     * @throws Exception
     */
    public function createGuzzleClientStub(int $statusCode, array $body): self
    {
        $this->guzzleClient = $this->createStub(ClientInterface::class);
        $this->guzzleClient->method('request')
            ->willReturn(new Response($statusCode, [], json_encode($body)));

        return $this;
    }

    public function createHttpClient(): self
    {
        $this->httpClient = new HttpClient($this->guzzleClient);

        return $this;
    }

    /**
     * @param class-string $classname
     */
    public function createProvider(string $classname): AbstractProvider
    {
        return new $classname($this->httpClient);
    }
}
