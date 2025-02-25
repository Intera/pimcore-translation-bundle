<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace DivanteTranslationBundle\Provider;

use DivanteTranslationBundle\Exception\TranslationException;
use Symfony\Component\HttpFoundation\Request;

class GoogleProvider extends AbstractProvider
{
    protected string $url = 'https://www.googleapis.com/';

    /**
     * @inheritDoc
     */
    public function translate(string $data, string $targetLanguage): string
    {
        $data = $this->client->request(
          Request::METHOD_GET,
            'language/translate/v2',
            [
                'query' => [
                    'key' => $this->apiKey,
                    'q' => $data,
                    'source' => '',
                    'target' => locale_get_primary_language($targetLanguage),
                ]
            ]
        );

        if ($data['error'] ?? false) {
            throw new TranslationException();
        }

        return $data['data']['translations'][0]['translatedText'];
    }

    public function getName(): string
    {
        return 'google_translate';
    }
}
