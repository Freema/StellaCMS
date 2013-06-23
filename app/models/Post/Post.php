<?php
namespace Models\Post;

use Doctrine\ORM\EntityManager;
use Nette\Object;
/**
 * Description of Article
 *
 * @author Tomáš Grasl
 */
class Post extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    /** @var array */
    protected $filter;
    
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
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
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
     * @return Models\Entity\Post\Post
     */
    public function getPostRepository()
    {
        return $this->_em->getRepository('Models\Entity\Post\Post');
    }
    
    /**
     * @return integer
     */
    public function postItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(p.id)');
        $query->from('Models\Entity\Post\Post', 'p');
        
        return $query->getQuery()->getSingleScalarResult();
    }    

    public function loadPostTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('p');
        $query->from('Models\Entity\Post\Post', 'p');
        $query->leftJoin('p.users', 'u');
        $query->leftJoin('p.category', 'c');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('p.id', $this->sort['id']);
                }
            }
            
            if(isset($this->sort['title']))
            {
                if(in_array($this->sort['title'], $sort_typs))
                {
                    $query->addOrderBy('p.title', $this->sort['title']);
                }
            }
            
            if(isset($this->sort['author']))
            {
                if(in_array($this->sort['author'], $sort_typs))
                {
                    $query->addOrderBy('u.username', $this->sort['author']);
                }
            }
            
            if(isset($this->sort['categorii']))
            {
                if(in_array($this->sort['categorii'], $sort_typs))
                {
                    $query->addOrderBy('c.title', $this->sort['categorii']);
                }
            }
            
            if(isset($this->sort['public']))
            {
                if(in_array($this->sort['public'], $sort_typs))
                {
                    $query->addOrderBy('p.publish', $this->sort['public']);
                }
            }
            
            if(isset($this->sort['uploadet_at']))
            {
                if(in_array($this->sort['uploadet_at'], $sort_typs))
                {
                    $query->addOrderBy('p.createdAt', $this->sort['uploadet_at']);
                }
            }
        }        
        
        if(!empty($this->filter))
        {
            $query->where('c.id = ?1');
            $query->setParameter(1, $this->filter);
        }
        
        $query->setMaxResults($this->maxResults);
        $query->setFirstResult($this->firstResult);
        
        return $query->getQuery()->getResult();        
    }
    
    public function deleteArticle($id)
    {
        $category = $this->getPostRepository()->getOne($id);
        $this->_em->remove($category);
        return $this->_em->flush();
    }        
}
