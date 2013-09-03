<?php
namespace Models\Entity\SlideShow;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Models\Image\ImageCategory;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\SlideShow\SlideShowRepository")
 * @ORM\Table(name="slide_show")
 * @ORM\HasLifecycleCallbacks
 */
class SlideShow
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=100)
     */    
    protected $title;
    
    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\SlideShow\SlideShowScript")
     * @ORM\JoinColumn(name="script_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $script;

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
    protected $updateAt;

    public function __construct($file)
    {
        $this->file = (string) $file;
        $this->name = '';
        $this->title = '';
        $this->script = NULL;
        $this->category = NULL;
        $this->imageOrder = NULL;
        $this->updateAt = $this->updateAt = new DateTime('now');
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->updateAt = new DateTime('now');
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
    public function getFileName()
    {
        return $this->file;        
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
     * @return SlideShow
     */
    public function setName($name)
    {
        $this->name = (string) $name;
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
     * @return SlideShow
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    /**
     * @return SlideShowScript
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param SlideShowScript $description
     * @return SlideShow
     */
    public function setScript(SlideShowScript $script)
    {
        $this->description = $script;
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
     * @return SlideShow
     */
    public function setCategory(ImageCategory $category)
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Set Category to NULL
     * 
     * @return SlideShow
     */
    public function removeCategory()
    {
        $this->category = NULL;
        return $this;
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
     * @return DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
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
     * @return SlideShow
     */
    public function setImageOrder($order)
    {
        $this->imageOrder = $order;
        
        return $this;
    }
}