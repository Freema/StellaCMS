<?php

namespace Models\Entity\Category;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CategoryRepository")
 * @ORM\Table(name="category")
 */
class Category
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
     * @ORM\Column(type="string", length=32)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $publish;
    
    /**
     * @ORM\Column(type="string", length=50) 
     */
    protected $cssClass;    

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
        $this->publish = TRUE;
        $this->cssClass = "";
        $this->children = new \Doctrine\Common\Collections\ArrayCollection;
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection;
    }
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return stirng
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = (string) $slug;
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
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    /**
     * @return array | string
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $categories
     * @return Category
     */
    public function setChildren($categories)
    {
        $this->children[] = $categories;
        return $this;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * @return Category
     */
    public function removeParent()
    {
        $this->parent = NULL;
        return $this;
    }
    
    /**
     * Get Publish
     * 
     * @return bool
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set Publish
     * 
     * @param bool $publish
     * @return Category
     */
    public function setPublish($publish)
    {
        $this->publish = (bool) $publish;
        return $this;
    } 
    
    /**
     * get Css class form Link table
     * 
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }
    
    /**
     * set CSS class
     * 
     * @param string $css
     * @return Category
     */    
    public function setCssClass($css)
    {
        $this->cssClass = (string) $css;
        
        return $this;
    }    
}
