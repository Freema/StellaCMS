<?php
namespace Models\Entity;
/**
 * Description of TagRepository
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ImageCategoryRepository")
 * @ORM\Table(name="image_category")
 */
class ImageCategory
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
    protected $title;
    
    /**
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="category")
     */
    protected $image;

    public function __construct($title, $slug, $description)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->image = new ArrayCollection;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function setSlug($slug)
    {
        $this->slug = (string) $slug;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }
}
