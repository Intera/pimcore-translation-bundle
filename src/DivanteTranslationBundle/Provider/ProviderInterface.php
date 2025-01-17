<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace DivanteTranslationBundle\Provider;

use DivanteTranslationBundle\Exception\TranslationException;

interface ProviderInterface
{
    /**
     * @throws TranslationException
     */
    public function translate(string $data, string $targetLanguage): string;
    public function setApiKey(string $apiKey): self;
    public function getName(): string;
}
