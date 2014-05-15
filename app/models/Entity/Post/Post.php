<?php
namespace Models\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Models\Entity\Category;
use Models\Entity\Comment;
use Models\Entity\PagePosition;
use Models\Entity\Tag;
use Models\Entity\User;

/**
 * @ORM\Entity(repositoryClass="PostRepository")
 * @ORM\Table(name="post")
 */
class Post extends BaseEntity
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
     * @ORM\Column(type="string", length=32)
     */
    protected $slug;        
    
    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
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
    * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
    * @ORM\JoinTable(name="post_tags")
    */
    protected $tags;    
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="posts", cascade={"persist","remove"})
     */
    protected $comment;  
    
    /**
     * @ORM\OneToOne(targetEntity="Models\Entity\PagePosition")
     * @ORM\JoinColumn(name="pagePosition_id", referencedColumnName="id")
     */    
    protected $pagePostion;    

    public function __construct(User $users, $content, $title) {
        $this->users = $users;
        $this->content = $content;
        $this->title = $title;
        $this->publish = TRUE;
        $this->clicks = 0;
        $this->createdAt = new DateTime;
        $this->tags = new ArrayCollection;  
        $this->comment = new ArrayCollection;    
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
     * @return Post
     */    
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }    

    /**
     * Get User
     * 
     * @return User
     */
    public function getUser()
    {
        return $this->users;
    }

    /**
     * Set User
     * 
     * @param User $users
     * @return Post
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
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }
    
    /**
     * Get Category
     * 
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Category
     * 
     * @param Category $category
     * @return Post
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Set Category to NULL
     * 
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
     */
    public function setCreatedAt(DateTime $created)
    {
        $this->createdAt = $created;
        return $this;
    }    
    
    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     * @return Post
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
        return $this;
    }
    
    /**
     * @return PagePosition
     */
    public function getPagePosition()
    {
        return $this->pagePostion;
    }
    
    /**
     * @param PagePosition $position
     * @return Post
     */
    public function setPagePosition(PagePosition $position)
    {
        $this->pagePostion = $position;
        return $this;
    }
    
    /**
     * @return Post
     */
    public function removePagePosition()
    {
        $this->pagePostion = NULL;
        return $this;        
    }    
}