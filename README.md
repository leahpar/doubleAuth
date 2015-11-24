doubleAuth
==========

A quick-and-dirty Symfony 2.7 project as an exercise to test double password authentication.

## Custom password authenticator

http://symfony.com/doc/current/cookbook/security/custom_password_authenticator.html

### Class DoublePasswordAuthenticator

Check classic user password and check master password.
 
### Class DoublePasswordToken

Extends UsernamePasswordToken whith a master password attribute. Master password is retrieved in the `Request` as a POST variable.

## Configuration

`DoublePasswordAuthenticator` is a service defined in `services.yml`.

Firewalls can use this service with `simple_form.authenticator` defined in `security.yml`. 

## Master password

Master password is currently stored as plaintext in `parameters.yml`.

You may want to encrypt it, using an encoder like [BCryptPasswordEncoder](https://github.com/symfony/security-core/blob/master/Encoder/BCryptPasswordEncoder.php).

You may also want to store it in some admin account and retrieve it with the `EntityManager`. 