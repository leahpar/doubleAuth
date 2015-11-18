<?php

namespace AppBundle\Security;


use AppBundle\Token\UserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Util\StringUtils;

class AuthProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param TokenInterface $token
     * @return UserToken
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user && $this->validateDigest($user)) {
            $authenticatedToken = new UserToken();
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('Authentication failed');
    }


    protected function validateDigest($user)
    {

        if ($user->getPassword() == "toto") {
            return true;
        }
        else {
            return false;
        }
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof UserToken;
    }
}