<?php
namespace Models\Image;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of ImageCategory
 *
 * @author Tomáš Grasl
 */
class ImageCategory extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    /** @var array */
    protected $sort;
    
    /** @var integer */
    protected $page;
    
    /** @var integer */
    protected $maxResults;
    
    /** @var integer */
    protected $firstResult;     
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    /**
     * @param array $sort
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;
    }
    
    /**
     * @param integer $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
    }
    
    /**
     * @param integer $result
     */
    public function setMaxResults($result)
    {
        $this->maxResults = (int) $result;
    }
    
    /**
     * @param integer $result
     */
    public function setFirstResult($result)
    {
        $this->firstResult = (int) $result;
    }        
    
    /**
     * @return Models\Entity\ImageCategory
     */
    public function getImageCategoryRepository()
    {
        return $this->_em->getRepository('Models\Entity\ImageCategory');
    }
    
    /**
     * @return integer
     */
    public function categoryItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(c.id)');
        $query->from('Models\Entity\ImageCategory', 'c');
        
        return $query->getQuery()->getSingleScalarResult();
    }      

    public function loadImageCategoryTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('c.id, c.title, c.slug, c.description, COUNT(i.id) AS images');
        $query->from('Models\Entity\ImageCategory', 'c');
        $query->leftJoin('c.image', 'i');
        $query->groupBy('c.id');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('c.id', $this->sort['id']);
                }
            }
            
            if(isset($this->sort['title']))
            {
                if(in_array($this->sort['title'], $sort_typs))
                {
                    $query->addOrderBy('c.title', $this->sort['title']);
                }
            }
            
            if(isset($this->sort['slug']))
            {
                if(in_array($this->sort['slug'], $sort_typs))
                {
                    $query->addOrderBy('c.slug', $this->sort['slug']);
                }
            }
            
            if(isset($this->sort['images']))
            {
                if(in_array($this->sort['images'], $sort_typs))
                {
                    $query->addOrderBy('images', $this->sort['images']);
                }
            }
        }        

        $query->setMaxResults($this->maxResults);
        $query->setFirstResult($this->firstResult);
        
        return $query->getQuery()->getResult();   
        
        
        return $query->getResult();
    }
    
    public function deleteCategory($id)
    {
        $category = $this->getImageCategoryRepository()->getOne($id);
        $this->_em->remove($category);
        return $this->_em->flush();
    }
}
