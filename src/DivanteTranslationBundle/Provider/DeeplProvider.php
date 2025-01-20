<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace DivanteTranslationBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

class DeeplProvider extends AbstractProvider implements FormalityProviderInterface
{
    protected string $url = 'https://api.deepl.com/';
    protected string $formality = 'default';

    public function setFormality(?string $formality): self
    {
        $this->formality = $formality ?? $this->formality;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function translate(string $data, string $targetLanguage): string
    {
        $data = $this->client->request(
            Request::METHOD_POST,
            'v2/translate',
            [
                'query' => [
                    'auth_key' => $this->apiKey,
                    'text' => $data,
                    'target_lang' => locale_get_primary_language($targetLanguage),
                    'formality' => $this->formality
                ]
            ]
        );

        return $data['translations'][0]['text'] ?? '';
    }

    public function getName(): string
    {
        return 'deepl';
    }
}
