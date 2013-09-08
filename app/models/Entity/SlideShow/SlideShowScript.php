<?php

namespace Models\Entity\SlideShow;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Json;

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
     * @ORM\Column(type="text")
     */
    protected $options;
    
    /**
     * @ORM\OneToMany(targetEntity="Models\Entity\SlideShow\SlideShow", mappedBy="script")
     */
    protected $slideshow;    

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
}
