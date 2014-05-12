<?php
namespace Models\Entity;
/**
 * Description of TagRepository
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SlideShowRepository")
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
     * @ORM\Column(type="text")
     */    
    protected $title;
    
    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\SlideShowScript", inversedBy="slideshow")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $script;
    
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
        $this->script = new ArrayCollection;   
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
        $this->script = $script;
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