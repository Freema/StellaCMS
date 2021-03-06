<?php
namespace Models\Entity;
/**
 * Description of TagRepository
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Models\Entity\Image;

/**
 * @ORM\Entity(repositoryClass="CategoryRepository")
 * @ORM\Table(name="category")
 */
class Category extends BaseEntity {
    
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
     * @ORM\OneToMany(targetEntity="Models\Entity\Category", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Models\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    protected $posts;
    
    /**
     * @ORM\ManyToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $images;

    public function __construct($title, $slug, $description) {
        parent::__construct();
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->publish = TRUE;
        $this->cssClass = "";
        $this->children = new ArrayCollection;
        $this->posts = new ArrayCollection;
        $this->image = new ArrayCollection;
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
    
    /**
     * Get Image
     * 
     * @return Image
     */
    public function getCategory()
    {
        return $this->images;
    }

    /**
     * Set Image
     * 
     * @param Image $category
     * @return Category
     */
    public function setImage(Image $category)
    {
        $this->images = $category;
        return $this;
    }
    
    /**
     * Set Image to NULL
     * 
     * @return Category
     */
    public function removeImage()
    {
        $this->images = NULL;
        return $this;
    }    
    
    /**
     * @return array
     */
    public function getPost() {
        return $this->posts;
    }
    
}
