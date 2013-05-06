<?php
namespace Models\Entity\Tag; 
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Tag\TagRepository")
 * @ORM\Table(name="tag")
 */

class Tag
{
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $name;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=40, unique=true) 
     */
    private $slug;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255) 
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $quantifier;

    /**
     * Get id
     *
     * @return integer 
     */
    
    public function __construct($name) {
        $this->name = $name;
        $this->slug = NULL;
        $this->description = NULL;
        $this->quantifier = 0;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Models\Entity\Tag\Tag
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get slug
     * 
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     * 
     * @param type $slug
     * @return \Models\Entity\Tag\Tag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
    
    /**
     * Get description
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set description
     * 
     * @param type $description
     * @return \Models\Entity\Tag\Tag
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }

    /**
     * Get quantifier
     *
     * @return integer 
     */
    public function getQuantifier()
    {
        return $this->quantifier;
    }

    /**
     * Set quantifier
     *
     * @param integer $quantifier
     * @return \Models\Entity\Tag\Tag
     */
    public function setQuantifier($quantifier)
    {
        $this->quantifier = $quantifier;
    
        return $this;
    }
}

