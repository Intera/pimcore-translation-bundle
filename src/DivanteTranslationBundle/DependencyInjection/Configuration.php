<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace DivanteTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('divante_translation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                ->end()
                ->scalarNode('source_lang')
                    ->isRequired()
                ->end()
                ->scalarNode('provider')
                    ->defaultValue('google_translate')
                ->end()
                ->scalarNode('formality')
                    ->defaultValue('default')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
