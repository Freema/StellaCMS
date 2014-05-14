<?php

namespace Models\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Models\Entity\Post;

/**
 * @ORM\Table(name="page_position")
 * @ORM\Entity()
 */
class PagePosition extends BaseEntity
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
    protected $name;
    
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;    
    
    /**
     * @ORM\OneToOne(targetEntity="Models\Entity\Post", mappedBy="pagePostion")
     **/
   protected $post;    

    public function __construct($name) {
        $this->name = $name;
        $this->createdAt = new DateTime("now");
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return stirng
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = (string) $name;
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
}
