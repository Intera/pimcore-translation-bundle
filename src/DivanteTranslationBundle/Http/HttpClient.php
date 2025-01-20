<?php

declare(strict_types=1);

namespace DivanteTranslationBundle\Http;

use DivanteTranslationBundle\Exception\TranslationException;
use GuzzleHttp\ClientInterface;

class HttpClient implements HttpClientInterface
{
    private string $baseUri;

    public function __construct(private ClientInterface $client)
    {
    }

    public function setBaseUri(string $uri): self
    {
        if (!str_ends_with($uri, '/')) {
            $uri .= '/';
        }

        $this->baseUri = $uri;

        return $this;
    }

    /**
     * @throws TranslationException
     */
    public function request(string $method, string $uri, array $options): mixed
    {
        $requestUri = $this->createRequestUri($this->baseUri, $uri);

        try {
            $result = $this->client->request(
                method: $method,
                uri: $requestUri,
                options: $options,
            );

            $body = $result->getBody()->getContents();
            $data = json_decode($body, true);
        } catch (\Throwable $exception) {
            throw new TranslationException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $data;
    }

    private function createRequestUri(string $baseUri, string $uri): string
    {
        if (str_starts_with($uri, '/')) {
            $uri = substr($uri, 1);
        }

        return sprintf('%s%s', $baseUri, $uri);
    }
}
