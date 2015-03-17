<?php

namespace Airlines\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineEntityListenerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @see http://egeloen.fr/2013/12/01/symfony2-doctrine2-entity-listener-as-serice/
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('airlines.entity_listener_resolver');
        $services = $container->findTaggedServiceIds('doctrine.entity_listener');

        foreach ($services as $service => $attributes) {
            $definition->addMethodCall(
                'addMapping',
                [$container->getDefinition($service)->getClass(), $service]
            );
        }
    }
}
