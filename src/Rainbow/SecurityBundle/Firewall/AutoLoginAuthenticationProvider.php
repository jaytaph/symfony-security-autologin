<?php

namespace Rainbow\SecurityBundle\Firewall;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AutoLoginAuthenticationProvider implements AuthenticationProviderInterface
{

    private $providerKey;

    public function __construct($providerKey)
    {
        $this->providerKey = $providerKey;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof AutoLoginToken && $token->getProviderKey() === $this->providerKey;
    }


    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }


        /*
         * Note: we check the given autologin key against on getPassword(). This is because we can use the
         * in_memory user provider. Otherwise, we have to create a user database with extra "autologin_key"
         * column, and match against that. However, this might be a good exercise for the reader (hint:
         * create a custom AutoLoginUser interface with a "getKey()" method).
         */

        // Check if the secret in the token matches the one the actual user
        if (hash_equals($token->getSecret(), $token->getUser()->getPassword()) !== true) {
            throw new AuthenticationException('Authentication failed.');
        }

        $authenticatedToken = new AutoLoginToken($token->getUser(), $this->providerKey, 'autologin');
        $authenticatedToken->setAttributes($token->getAttributes());

        return $authenticatedToken;
    }

}
