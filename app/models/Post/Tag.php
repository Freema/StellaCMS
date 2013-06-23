<?php
namespace Models\Tag;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of Tag
 *
 * @author Tomáš Grasl
 */
class Tag extends Object {
    
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
     * @return \Models\Entity\Tag\Tag
     */
    public function getTagRepository()
    {
        return $this->_em->getRepository('Models\Entity\Tag\Tag');
    }
    
    /**
     * @return integer
     */
    public function tagItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(t.id)');
        $query->from('Models\Entity\Tag\Tag', 't');
        
        return $query->getQuery()->getSingleScalarResult();
    }      

    public function loadTagTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('t.id, t.name, t.slug, t.description, COUNT(p.id) AS posts');
        $query->from('Models\Entity\Tag\Tag', 't');
        $query->leftJoin('t.posts', 'p');
        $query->groupBy('t.id');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('t.id', $this->sort['id']);
                }
            }
            
            if(isset($this->sort['title']))
            {
                if(in_array($this->sort['title'], $sort_typs))
                {
                    $query->addOrderBy('t.name', $this->sort['title']);
                }
            }
            
            if(isset($this->sort['slug']))
            {
                if(in_array($this->sort['slug'], $sort_typs))
                {
                    $query->addOrderBy('t.slug', $this->sort['slug']);
                }
            }
            
            if(isset($this->sort['posts']))
            {
                if(in_array($this->sort['posts'], $sort_typs))
                {
                    $query->addOrderBy('posts', $this->sort['posts']);
                }
            }
        }        

        $query->setMaxResults($this->maxResults);
        $query->setFirstResult($this->firstResult);
        
        return $query->getQuery()->getResult(); 
    }
    
    public function deleteTag($id)
    {
        $tag = $this->getTagRepository()->getOne($id);
        $this->_em->remove($tag);
        return $this->_em->flush();
    }
}
