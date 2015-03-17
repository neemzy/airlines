<?php

namespace Airlines\AppBundle;

use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityListenerResolver extends DefaultEntityListenerResolver
{
    /** @var ContainerInterface */
    private $container;

    /** @var array */
    private $mapping;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->mapping = [];
    }

    public function addMapping($className, $service)
    {
        $this->mapping[$className] = $service;
    }

    /**
     * Resolves event listener class names
     * This allows us to provide our own services instead, as such, we have control over their dependencies
     *
     * @return mixed
     */
    public function resolve($className)
    {
        if (array_key_exists($className, $this->mapping) && $this->container->has($this->mapping[$className])) {
            return $this->container->get($this->mapping[$className]);
        }

        return parent::resolve($className);
    }
}
