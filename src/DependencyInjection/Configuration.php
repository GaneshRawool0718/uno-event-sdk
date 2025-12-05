<?php

namespace Uno\EventSdk\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{   
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {   
        /* Define the root node for the configuration tree */
        $treeBuilder = new TreeBuilder('uno_event_sdk');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('default_metadata')
                    ->info('Default metadata added to every published event.')
                    ->useAttributeAsKey('key')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()

                ->scalarNode('logs_url')
                    ->info('HTTP endpoint where SDK HttpApiEventSubscriber will POST events.')
                    ->defaultNull()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
