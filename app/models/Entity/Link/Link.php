<?php

namespace Models\Entity\Link;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="LinkRepository")
 * @ORM\Table(name="link")
 */
class Link extends \Nette\Object
{
    const TARGET_BLANK = '_blank';
    const TARGET_TOP = '_top';
    const TARGET_NONE = '_none';
    
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
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $url;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=50) 
     */
    protected $target;
    
    /**
    * @ORM\Column(name="created_at", type="datetime")
    */
    protected $createdAt;

    public function __construct($title, $url, $description)
    {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->target = NULL;
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
        $this->title = (string) $title;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setUrl($url)
    {
        $this->slug = (string) $url;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        if(!in_array($target, array(self::TARGET_BLANK, self::TARGET_NONE, self::TARGET_TOP)))
        {
            throw new \InvalidArgumentException('Invalid taget!');
        }
        
        $this->target = $target;
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
