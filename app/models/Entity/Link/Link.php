<?php

namespace Models\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="LinkRepository")
 * @ORM\Table(name="link")
 */
class Link
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
     * @ORM\Column(type="string", length=100)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=50) 
     */
    protected $target;
    
    /**
     * @ORM\Column(type="string", length=50) 
     */
    protected $cssClass;

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
        $this->cssClass = NULL;
        $this->createdAt = new DateTime;
    }
    
    /**
     * get ID row form Link table
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * get Title form Link table
     *  
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * set Title
     * 
     * @param string $title
     * @return \Models\Entity\Link\Link
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }
    
    /**
     * get Url form Link table
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * set Url
     * 
     * @param stirn $url
     * @return \Models\Entity\Link\Link
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
        return $this;
    }

    /**
     * get Description form Link table
     *  
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * set Description 
     * 
     * @param string $description
     * @return \Models\Entity\Link\Link
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    /**
     * get Targent from Link table
     *  
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * set Target
     * 
     * @param string $target
     * @return \Models\Entity\Link\Link
     * @throws \InvalidArgumentException
     */
    public function setTarget($target)
    {
        if(!$target == NULL )
        {
            if(!in_array($target, array(self::TARGET_BLANK, self::TARGET_NONE, self::TARGET_TOP)))
            {
                throw new \InvalidArgumentException('Invalid taget!');
            }
        }
        
        $this->target = $target;
        return $this;
    }
    
    /**
     * get Css class form Link table
     * 
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }
    
    /**
     * set CSS class
     * 
     * @param string $css
     * @return \Models\Entity\Link\Link
     */    
    public function setCssClass($css)
    {
        $this->cssClass = (string) $css;
        
        return $this;
    }
            
    /**
     * get Datetime form Link table 
     * 
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * set CreateAt
     * 
     * @param DateTime $created
     * @return \Models\Entity\Link\Link
     */
    public function setCreatedAt(DateTime $created)
    {
        $this->createdAt = $created;
        return $this;
    }  
}
