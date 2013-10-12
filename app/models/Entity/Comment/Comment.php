<?php
namespace Models\Entity\Comment; 

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Models\Entity\Post\Post;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks 
 */
class Comment
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
     * 
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="emailAddress", type="string", length=100)
     */
    private $emailAddress;
    
    /**
     * @var integer | bool
     * 
     * @ORM\Column(name="approve", type="boolean")
     */
    private $approve;
    
    /**
     * @var type 
     * 
     * @ORM\Column(name="user", type="string", length=100)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="ipAdress", type="string", length=100)
     */
    private $ipAdress;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="update_at", type="datetime")
     */
    private $updateAt;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    protected $parent;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\Post\Post", inversedBy="comment")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id",nullable=false)
     */
    private $posts;  
    
    public function __construct() {
        $this->approve = TRUE;
        $this->emailAdress = '';
        $this->createdAt = new DateTime();
        $this->updateAt = new DateTime();
        $this->posts = new ArrayCollection();
        $this->children = new ArrayCollection;
        $this->posts = new ArrayCollection;        
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpadate()
    {
        $this->updateAt = new DateTime();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Comment
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * set username
     * 
     * @param string $username
     * @return Comment
     */
    public function setUser($username)
    {
        $this->user = (string) $username;
        
        return $this;
    }
    
    /**
     * return username 
     * 
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @param string $ip
     * @return Comment
     */
    public function setIpAdress($ip)
    {
        $this->ipAdress = (string) $ip;
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getIpAdress()
    {
        return $this->ipAdress;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     * @return Comment
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updateAt
     *
     * @param DateTime $updateAt
     * @return Comment
     */
    public function setUpdateAt(DateTime $updateAt)
    {
        $this->updateAt = $updateAt;
    
        return $this;
    }

    /**
     * Get updateAt
     *
     * @return DateTime 
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
    
    /**
     * @param Post $post
     * @return Comment
     */
    public function setPost(Post $post)
    {
        $this->posts = $post;
        
        return $this;
    }
    
    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
    
    /**
     * @return bool
     */
    public function getApprove()
    {
        return (bool) $this->approve;
    }
    
    /**
     * @param bool $approve
     * @return Comment
     */
    public function setApprove($approve)
    {
        $this->approve = (bool) $approve;
        
        return $this;
    }
    
    /**
     * @param string $address
     * @return Comment
     */
    public function setEmailAddress($address)
    {
        $this->emailAddress = (string) $address;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    
    /**
     * @return array | string
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $categories
     * @return Comment
     */
    public function setChildren($categories)
    {
        $this->children[] = $categories;
        return $this;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * @param Comment $parent
     * @return Comment
     */
    public function setParent(Comment $parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * @return Comment
     */
    public function removeParent()
    {
        $this->parent = NULL;
        return $this;
    }    
}