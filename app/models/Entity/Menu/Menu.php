<?php
namespace Models\Entity; 
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\MenuRepository")
 * @ORM\Table(name="menu")
 */

class Menu {
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100, unique=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    protected $title;

    
    /**
     *
     * @var string
     * @ORM\Column(name="description", type="string", length=255)
     */
    protected $description;

    
    public function __construct($url, $title, $description)
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
    }    
    
    /**
     * Get id
     *
     * @return integer 
     */
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Url
     */
    public function setUrl($name)
    {
        $this->url = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param integer $quantifier
     * @return Title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return integer 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param integer $quantifier
     * @return Description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return integer 
     */
    public function getDescription()
    {
        return $this->description;
    }

}

