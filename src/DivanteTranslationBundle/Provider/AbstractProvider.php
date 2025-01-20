<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace DivanteTranslationBundle\Provider;

use DivanteTranslationBundle\Http\HttpClientInterface;

abstract class AbstractProvider implements ProviderInterface
{
    protected string $url;
    protected string $apiKey;

    public function __construct(protected HttpClientInterface $client)
    {
        $this->client->setBaseUri($this->url);
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
