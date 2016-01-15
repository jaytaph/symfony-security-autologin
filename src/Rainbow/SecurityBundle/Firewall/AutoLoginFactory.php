<?php

namespace Rainbow\SecurityBundle\Firewall;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class AutoLoginFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container,
                           $id,
                           $config,
                           $userProvider,
                           $defaultEntryPoint)
    {
        // Create authentication provider
        $providerId = 'security.authentication.provider.autologin.'.$id;
        $container
            ->setDefinition($providerId,
                new DefinitionDecorator('security.authentication.provider.autologin')
            )
            ->addArgument($id)
        ;

        // Create listener
        $listenerId = 'security.authentication.listener.autologin.'.$id;
        $container
            ->setDefinition($listenerId,
                new DefinitionDecorator('autologin.security.authentication.listener')
            )
            ->addArgument(new Reference($userProvider))
            ->addArgument($id)
        ;

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        // We place the autologin provider at "pre_auth" position. This will make sure that it will take
        // precedence over login-forms etc.
        return "pre_auth";
    }

    public function getKey()
    {
        return "autologin";
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        // Nothing to configure
    }

}
