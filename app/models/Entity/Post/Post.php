<?php
namespace Models\Entity\Post;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Models\Entity\Category\Category;
use Models\Entity\User\User;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Post\PostRepository")
 * @ORM\Table(name="post")
 */
class Post extends \Nette\Object
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $title;
    
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

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publish;

    /**
     * @ORM\Column(type="integer")
     */
    protected $clicks;
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    

    public function __construct(User $users, $content, $title)
    {
        $this->users = $users;
        $this->content = $content;
        $this->title = $title;
        $this->publish = TRUE;
        $this->clicks = 0;
        $this->createdAt = new DateTime;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
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
    
    public function getPublish()
    {
        return $this->publish;
    }

    public function setPublish($publish)
    {
        $this->publish = (bool) $publish;
        return $this;
    }    
    
    public function getClicks()
    {
        return $this->clicks;
    }

    public function setClicks($hit)
    {
        $this->clicks = (int) $hit;
        return $this;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $created)
    {
        $this->createdAt = $created;
        return $this;
    }    
}