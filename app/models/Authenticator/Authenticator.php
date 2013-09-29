<?php
namespace Models\Authenticator;

use Doctrine\ORM\EntityRepository;
use Nette\Object;
use Nette\Security as NS;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;


/**
 * Users authenticator.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class Authenticator extends Object implements NS\IAuthenticator
{
    /** @var EntityRepository */
    private $_users;

    /** @var stirng */
    private $_salt;


    public function __construct(EntityRepository $users, $salt)
    {
            $this->_users = $users;
            $this->_salt = $salt;
    }

    /**
     * Performs an authentication
     * @param  array
     * @return Identity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
            list($username, $password) = $credentials;
            
            /* @var $user \Models\Entity\User\User */
            $user = $this->_users->findOneBy(array('username' => $username));

            if (!$user) {
                    throw new NS\AuthenticationException("Uživatelské jmeno: '$username' nebylo nalezeno.", self::IDENTITY_NOT_FOUND);
            }

            if ($user->getPassword() !== $this->calculateHash($password)) {
                    throw new NS\AuthenticationException("Špatné heslo.", self::INVALID_CREDENTIAL);
            }

            return new NS\Identity($user->getId(), $user->getRole(), array(
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
            ));
    }



    /**
     * Computes salted password hash.
     * @param  string
     * @return string
     */
    public function calculateHash($password)
    {
            return md5($password . str_repeat('*enter any random salt here*', 10));
    }

    static function staticHash($password)
    {
            return md5($password . str_repeat('*enter any random salt here*', 10));
    }
    
}
