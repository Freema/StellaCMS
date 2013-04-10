<?php
namespace Models\Entity\Post;

use Doctrine\ORM\Mapping as ORM;
use Models\Entity\Category\Category;
use Models\Entity\User\User;
use Nette\Object;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Post\PostRepository")
 * @ORM\Table(name="post")
 */
class Post extends Object {

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
     * @ORM\ManyToOne(targetEntity="Models\Entity\Category\Category", inversedBy="posts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $content;
    

    public function __construct(User $users, $content)
    {
        $this->users = $users;
        $this->content = $content;
    }
    
    public function getId()
    {
        return $this->id;
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
    
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }    
}