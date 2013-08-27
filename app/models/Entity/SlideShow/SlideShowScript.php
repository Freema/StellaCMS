<?php

namespace Models\Entity\SlideShow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SlideShowRepository")
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
     * @ORM\Column(type="string", length=32)
     */
    protected $url;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     */
    protected $options;

    public function __construct($name, $url, $description)
    {
        $this->name = (string) $name;
        $this->url = (string) $url;
        $this->description = (string) $description;
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
        return $this->title;
    }
   
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->slug;
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
        $this->options = (string) $options;        
    }
}
