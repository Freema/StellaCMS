<?php

namespace Models\Entity\Image;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Models\Entity\ImageCategory\ImageCategory;

/**
 * @ORM\Entity(repositoryClass="ImageRepository")
 * @ORM\Table(name="image")
 * @ORM\HasLifecycleCallbacks() 
 */
class Image extends BaseEntity {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=4)
     */    
    protected $ext;
    
    /**
     * @ORM\Column(type="string", length=100)
     */    
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $public;

    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\ImageCategory\ImageCategory",  inversedBy="image")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $imageOrder;

    /**
     * @ORM\Column(type="datetime")
     */    
    protected $uploadedAt;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updateAt;

    public function __construct($file)
    {
        $this->file = (string) $file;
        
        $this->name = '';
        $this->ext = '';
        $this->description = '';
        $this->imageOrder = 0;
        $this->title = '';
        $this->public = true;
        $this->category = null;
        $this->uploadedAt = new DateTime;
        $this->updateAt = new DateTime;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function onUpadate()
    {
        $this->updateAt = new DateTime();
    }    
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->file;
    }

    /**
     * @param string $name
     * @return Image
     */
    public function setFileName($name)
    {
        $this->file = (string) $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     * @return Image
     */
    public function setExt($ext)
    {
        $this->ext = (string) $ext;
        return $this;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Image
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }
    
    /**
     * Get Category
     * 
     * @return ImageCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Category
     * 
     * @param ImageCategory $category
     * @return Image
     */
    public function setCategory(ImageCategory $category)
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Set Category to NULL
     * 
     * @return Image
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
        return $this->public;
    }

    /**
     * Set Publish
     * 
     * @param bool $public
     * @return Image
     */
    public function setPublish($public)
    {
        $this->public = (bool) $public;
        return $this;
    }       

    /**
     * @return string
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * @param DateTime $uploaded
     * @return Image
     */
    public function setUploadedAt(DateTime $uploaded)
    {
        $this->uploadedAt = $uploaded;
        return $this;
    }  
    
    /**
     * @return integer
     */
    public function getImageOrder()
    {
        return $this->imageOrder;
    }
    
    /**
     * set Image order
     * 
     * @param integer $order
     * @return Image
     */
    public function setImageOrder($order)
    {
        $this->imageOrder = $order;
        
        return $this;
    }
    
    /**
     * get Update time
     * 
     * @return DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
}
