<?php
namespace Models\Entity\Post;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Models\Entity\Category\Category;
use Models\Entity\Tag\Tag;
use Models\Entity\User\User;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Post\PostRepository")
 * @ORM\Table(name="post")
 */
class Post
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
    * @ORM\ManyToMany(targetEntity="Models\Entity\Tag\Tag", inversedBy="posts")
    * @ORM\JoinTable(name="post_tags")
    */
    protected $tags;    
    
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
        $this->tags = new ArrayCollection();        
    }
    
    /**
     * Get Id
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get Title
     *  
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set Title
     * 
     * @param string $title
     * @return \Models\Entity\Post\Post
     */    
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    /**
     * Get User
     * 
     * @return \Models\Entity\User\User
     */
    public function getUser()
    {
        return $this->users;
    }

    /**
     * Set User
     * 
     * @param \Models\Entity\User\User $users
     * @return \Models\Entity\Post\Post
     */
    public function setUser(User $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Get Content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set Content
     * 
     * @param type $content
     * @return \Models\Entity\Post\Post
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }
    
    /**
     * Get Category
     * 
     * @return \Models\Entity\Category\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Category
     * 
     * @param \Models\Entity\Category\Category $category
     * @return \Models\Entity\Post\Post
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Set Category to NULL
     * 
     * @return \Models\Entity\Post\Post
     */
    public function removeCategory()
    {
        $this->category = NULL;
        return $this;
    }
    
    /**
     * Get Publish
     * 
     * @return bool
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set Publish
     * 
     * @param bool $publish
     * @return \Models\Entity\Post\Post
     */
    public function setPublish($publish)
    {
        $this->publish = (bool) $publish;
        return $this;
    }    
    
    /**
     * 
     * Get Clicks
     * 
     * @return integer
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set Clicks
     * 
     * @param integer $hit
     * @return \Models\Entity\Post\Post
     */
    public function setClicks($hit)
    {
        $this->clicks = (int) $hit;
        return $this;
    }
    
    /**
     * Add tags
     *
     * @param Tag $tags
     * @return \Models\Entity\Post\Post
     */
    public function addTag(Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get Tag
     * 
     * @return Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }    
    
    
    /**
     * Get Create At
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set Create At
     * 
     * @param DateTime $created
     * @return \Models\Entity\Post\Post
     */
    public function setCreatedAt(DateTime $created)
    {
        $this->createdAt = $created;
        return $this;
    }    
}