<?php
namespace Models\Entity\Tag; 
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Models\Entity\Post\Post;

/**
 * @ORM\Entity(repositoryClass="TagRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="tag")
 */

class Tag extends BaseEntity {
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40, unique=true)
     */
    protected $name;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=40, unique=true) 
     */
    protected $slug;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255) 
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    
    protected $quantifier;

    /**
    * @ORM\ManyToMany(targetEntity="Models\Entity\Post\Post", mappedBy="tags")
    */
    private $posts;    
    
    final function __construct($name) {
        parent::__construct();
        $this->name = $name;
        $this->slug = NULL;
        $this->description = '';
        $this->quantifier = 0;
        $this->posts = new ArrayCollection();        
    }
    
    /** @ORM\prePersist */
    public function doOtherStuffOnPrePersist()
    {
        if($this->slug == NULL)
        {
            $this->setSlug($this->name);
        }
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
     * @return Tag
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
     * @return Tag
     */
    public function setSlug($slug)
    {
        $link = \Nette\Utils\Strings::webalize($slug);
        $this->slug = $link;
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
     * @return Tag
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
     * @return Tag
     */
    public function setQuantifier($quantifier)
    {
        $this->quantifier = $quantifier;
    
        return $this;
    }
    
    /**
     * Add posts
     *
     * @param Post $posts
     * @return Tag
     */
    public function addPost(Post $posts)
    {
        $this->posts[] = $posts;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param Post $posts
     */
    public function removePost(Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }    
}

