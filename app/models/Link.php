<?php
namespace Models\Link;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of Link
 *
 * @author Tomáš Grasl
 */
class Link extends Object {
    
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
     * @return Models\Entity\Link
     */
    public function getLinkRepository()
    {
        return $this->_em->getRepository('Models\Entity\Link');
    }
    
    /**
     * @return integer
     */
    public function linkItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(l.id)');
        $query->from('Models\Entity\Link', 'l');
        
        return $query->getQuery()->getSingleScalarResult();
    }      

    public function loadLinkTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('l.id, l.title, l.url, l.description, l.createdAt');
        $query->from('Models\Entity\Link', 'l');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('l.id', $this->sort['id']);
                }
            }
            
            if(isset($this->sort['title']))
            {
                if(in_array($this->sort['title'], $sort_typs))
                {
                    $query->addOrderBy('l.title', $this->sort['title']);
                }
            }
            
            if(isset($this->sort['slug']))
            {
                if(in_array($this->sort['slug'], $sort_typs))
                {
                    $query->addOrderBy('l.url', $this->sort['slug']);
                }
            }
            
            if(isset($this->sort['date']))
            {
                if(in_array($this->sort['date'], $sort_typs))
                {
                    $query->addOrderBy('l.createdAt', $this->sort['date']);
                }
            }
        }        

        $query->setMaxResults($this->maxResults);
        $query->setFirstResult($this->firstResult);
        
        return $query->getQuery()->getResult();         
    }
    
    public function deleteLink($id)
    {
        $link = $this->getLinkRepository()->getOne($id);
        $this->_em->remove($link);
        return $this->_em->flush();
    }
}
