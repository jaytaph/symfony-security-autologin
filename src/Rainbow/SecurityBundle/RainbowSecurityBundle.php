<?php

namespace Rainbow\SecurityBundle;

use Rainbow\SecurityBundle\Firewall\AutoLoginFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RainbowSecurityBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Add the auto login factory to the security extension
        $extension = $container->getExtension('security');
        if ($extension) {
            $extension->addSecurityListenerFactory(new AutoLoginFactory());
        }
    }

}
