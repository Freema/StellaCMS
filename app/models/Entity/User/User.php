<?php
namespace Models\Entity\User; 
/**
 * Description of User
 *
 * @author Tomáš
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */

class User extends \Nette\Object
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @param string
     * @return User
     */
    public function __construct($username)
    {
            $this->username = static::normalizeString($username);
    }

    /**
     * @return int
     */
    public function getId()
    {
            return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
            return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
            return $this->password;
    }

    /**
     * @param string
     * @return User
     */
    public function setPassword($password)
    {
            $this->password = static::normalizeString($password);
            return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
            return $this->email;
    }

    /**
     * @param string
     * @return User
     */
    public function setEmail($email)
    {
            $this->email = static::normalizeString($email);
            return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
            return $this->role;
    }

    /**
     * @param string
     * @return User
     */
    public function setRole($role)
    {
            $this->role = static::normalizeString($role);
            return $this;
    }

    /**
     * @param string
     * @return string
     */
    protected static function normalizeString($s)
    {
            $s = trim($s);
            return $s === "" ? NULL : $s;
    }

}
