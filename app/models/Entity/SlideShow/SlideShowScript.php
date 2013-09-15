<?php

namespace Models\Entity\SlideShow;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Json;
use Models\Entity\ImageCategory\ImageCategory;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\SlideShow\SlideShowRepository")
 * @ORM\Table(name="slide_show_script")
 */
class SlideShowScript
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
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $script_id;

    /**
     * @ORM\Column(type="text")
     */
    protected $options;
    
    /**
     * @ORM\OneToMany(targetEntity="Models\Entity\SlideShow\SlideShow", mappedBy="script")
     */
    protected $slideshow; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\ImageCategory\ImageCategory",  inversedBy="image")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;    

    public function __construct($name, $description)
    {
        $this->name = (string) $name;
        $this->description = (string) $description;
        $this->slideshow = new ArrayCollection;        
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getSlideShow()
    {
        return $this->slideshow;
    }
    
    /**
     * set new name for the slideshow script
     * 
     * @param string $name
     * @return \Models\Entity\SlideShow\SlideShowScript
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * set description 
     * 
     * @param type $text
     * @return \Models\Entity\SlideShow\SlideShowScript
     */
    public function setDescription($text)
    {
        $this->description = $text;
        
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
     * @return integer
     */
    public function getScriptId()
    {
        return $this->script_id;
    }
    
    /**
     * @param integer $id
     * @return SlideShowScript
     */
    public function setScriptId($id)
    {
        $this->script_id = (int) $id;
        return $this;        
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * @param srtring $options
     */
    public function setOptions($options)
    {
        $this->options = Json::encode($options);        
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
     * Set Category to NULL
     * 
     * @return SlideShowScript
     */
    public function removeCategory()
    {
        $this->category = NULL;
        return $this;
    }    

    /**
     * Set Category
     * 
     * @param ImageCategory $category
     * @return SlideShowScript
     */
    public function setCategory(ImageCategory $category)
    {
        $this->category = $category;
        return $this;
    }    
}
