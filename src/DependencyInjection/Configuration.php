<?php

declare(strict_types=1);

namespace KunicMarko\SonataImporterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_importer');

        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('sonata_importer');
        }

        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('form')->defaultValue('@SonataImporter/form.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('action_button')
                            ->defaultValue('@SonataImporter/action_button.html.twig')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('dashboard_action')
                            ->defaultValue('@SonataImporter/dashboard_action.html.twig')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
