<?php

namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DoublePasswordAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder;
    private $masterPassword;

    public function __construct(UserPasswordEncoderInterface $encoder, $mp_password)
    {
        $this->encoder = $encoder;

        // or use EntityManager to retrieve master password from admin account for example
        $this->masterPassword = $mp_password;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('User not found');
        }

        $passwordValid       = $this->encoder->isPasswordValid($user, $token->getCredentials());
        $masterPasswordValid = ($this->masterPassword == $token->getMasterPassword());

        if ($passwordValid && $masterPasswordValid) {
            return new DoublePasswordToken(
                $user,
                $token->getCredentials(),
                $token->getMasterPassword(),
                $providerKey,
                $user->getRoles()
            );
        }

        throw new AuthenticationException('Invalid user password or master password');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof DoublePasswordToken
        && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        $masterPassword = $request->request->get('_password2', '');
        return new DoublePasswordToken($username, $password, $masterPassword, $providerKey);
    }
}