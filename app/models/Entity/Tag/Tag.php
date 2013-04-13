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

class Tag  extends \Nette\Object
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantifier", type="integer")
     */
    private $quantifier;

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
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * Set quantifier
     *
     * @param integer $quantifier
     * @return Tag
     */
    public function setQuantifier($quantifier)
    {
        $this->quantifier = $quantifier;
    
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

}

