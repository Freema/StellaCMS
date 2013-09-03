<?php

namespace Models\Entity\SlideShow;

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

    public function __construct($name, $description)
    {
        $this->name = (string) $name;
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
