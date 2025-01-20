<?php

declare(strict_types=1);

namespace DivanteTranslationBundle\Http;

interface HttpClientInterface
{
    public function setBaseUri(string $uri): self;

    public function request(string $method, string $uri, array $options): mixed;
}
