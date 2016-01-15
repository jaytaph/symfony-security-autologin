<?php

namespace Rainbow\SecurityBundle\Firewall;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;


class AutoLoginListener implements ListenerInterface
{

    function __construct(TokenStorageInterface $tokenStorage,
                         AuthenticationManagerInterface $authenticationManager,
                         UserProviderInterface $userProvider, $providerKey)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->userProvider = $userProvider;
        $this->providerKey = $providerKey;
    }

    public function handle(GetResponseEvent $event)
    {
        // Don't do anything when the auto_login query parameter is not found
        if (! $autoLogin = $event->getRequest()->get('auto_login', false)) {
            return;
        }

        # Decode the parameter and split into username and key.
        $autoLogin = base64_decode($autoLogin);
        list($username, $autoLoginKey) = explode(':', $autoLogin);

        # Find the user in the user provider for the given class
        try {
            $user = $this->userProvider->loadUserByUsername($username);
        } catch (\Exception $ex) {
            if (!$ex instanceof AuthenticationException) {
                $ex = new AuthenticationException($ex->getMessage(), $ex->getCode(), $ex);
            }

            throw $ex;
        }

        // Try and authenticate the token
        try {
            $token = $this->authenticationManager->authenticate(
                new AutoLoginToken($user, $this->providerKey, $autoLoginKey)
            );
        } catch (AuthenticationException $e) {
            return;
        }

        // If everything is ok, store the received authenticated token
        if ($token) {
            $this->tokenStorage->setToken($token);
        }
    }
}
