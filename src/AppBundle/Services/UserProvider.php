<?php

namespace AppBundle\Services;


use AppBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $user = new User($username, 'toto', 'toto', null, array('ROLE_USER'));
        dump("UserProvider", $user);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'DoubleAuth\AppBundle\Entity/User';
    }

}