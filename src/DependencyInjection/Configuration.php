<?php

namespace ICS\CronBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treebuilder = new TreeBuilder('cron');

        $treebuilder->getRootNode()
        ->children()
            ->scalarNode('timezone')->defaultValue('Europe/Paris')->end()
        ->end()
        ;

        return $treebuilder;
    }
}
