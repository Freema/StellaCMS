<?php
namespace Models\Entity\Menu; 
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Menu\MenuRepository")
 * @ORM\Table(name="menu")
 */

class Menu extends \Nette\Object {
    
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

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
     * Set quantifier
     *
     * @param integer $quantifier
     * @return Tag
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get quantifier
     *
     * @return integer 
     */
    public function getTitle()
    {
        return $this->title;
    }

}

