<?php

namespace Airlines\AppBundle;

use Airlines\AppBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AirlinesAppBundle extends Bundle
{
    /**
     * @see http://egeloen.fr/2013/12/01/symfony2-doctrine2-entity-listener-as-serice/
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineEntityListenerPass());
    }
}
