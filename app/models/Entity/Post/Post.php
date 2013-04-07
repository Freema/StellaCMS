<?php
namespace Models\Entity\Post; 

use Doctrine\ORM\Mapping as ORM;
use Models\Entity\User\User;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Post\PostRepository")
 * @ORM\Table(name="post")
 */
class Post extends \Nette\Object {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\User\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $users;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    public function __construct(User $users, $content)
    {
        $this->users = $users;
        $this->content = $content;
    }

    public function getUser()
    {
        return $this->users;
    }

    public function setUser(User $users)
    {
        $this->users = $users;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }
}