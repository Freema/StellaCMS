<?php

namespace Models\Entity\Category;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CategoryRepository")
 * @ORM\Table(name="category")
 */
class Category extends \Nette\Object
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
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Models\Entity\Post\Post", mappedBy="category")
     */
    protected $posts;

    public function __construct($title, $slug, $description)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->children = new \Doctrine\Common\Collections\ArrayCollection;
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection;
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

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($categories)
    {
        $this->children[] = $categories;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(Category $parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    public function removeParent()
    {
        $this->parent = NULL;
        return $this;
                
    }
}
