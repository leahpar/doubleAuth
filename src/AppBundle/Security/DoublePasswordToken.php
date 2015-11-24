<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 24/11/2015
 * Time: 15:46
 */

namespace AppBundle\Security;


use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DoublePasswordToken extends UsernamePasswordToken {

    private $masterPassword;


    public function __construct($user, $credentials, $masterPassword, $providerKey, array $roles = array())
    {
        parent::__construct($user, $credentials, $providerKey, $roles);

        $this->masterPassword = $masterPassword;
    }

    /**
     * @return mixed
     */
    public function getMasterPassword()
    {
        return $this->masterPassword;
    }
}