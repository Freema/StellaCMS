<?php
namespace Components\Slideshow;
/**
 * Description of SlideShow
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityManager;
use Models\Entity\Post\SlideShowScript;
use Models\Entity\SlideShow\SlideShow;
use Nette\Object;

class SlideshowService extends Object  {
    
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
    
    /** @var array */
    public $type = array(
        array(
            'name'      => 'Twitter bootstrap carosel V3.0',
            'script'    => 'carousel.js',
            'options'   => array(
                    'interval'  => 5000
                ),
            ),
        array(
            'name'      => 'FlexSlider 2',
            'script'    => 'jquery.flexslider-min.js',
            'options'   => array(
                    'interval'  => 5000
                ),
        )
    );
    
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
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    /**
     * Entity mager for slide_show taleble. 
     * @return SlideShow
     */
    public function getSlideShowRepository()
    {
        return $this->_em->getRepository('Models\Entity\SlideShow\SlideShow');
    }    
    
    /**
     * Entity mager for slide_show_script taleble. 
     * @return SlideShowScript
     */
    public function getSlideShowScriptRepository()
    {
        return $this->_em->getRepository('Models\Entity\SlideShow\SlideShowScript');
    }

    /**
     * @param bool $explode
     * @return array
     */
    public function getScriptType($explode = TRUE)
    {
        if($explode == TRUE)
        {
            $return = array();
            foreach ($this->_type as $value)
            {
                $return[] = $value['name'];
            }
            
            return $return;
        }
        else
        {
            return $this->_type;
        }
    }
    
    /**
     * @return integer
     */
    public function SlideShowItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(s.id)');
        $query->from('Models\Entity\SlideShow\SlideShow', 's');
        
        return $query->getQuery()->getSingleScalarResult();
    }        
    
    /**
     * @return SlideShow
     */
    public function loadSlideShowTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('s');
        $query->from('Models\Entity\SlideShow\SlideShow', 's');
        $query->leftJoin('s.script', 'sc');
        $query->leftJoin('s.category', 'c');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('s.id', $this->sort['id']);
                }
            }
            
            if(isset($this->sort['file']))
            {
                if(in_array($this->sort['file'], $sort_typs))
                {
                    $query->addOrderBy('s.file', $this->sort['file']);
                }
            }
            
            if(isset($this->sort['name']))
            {
                if(in_array($this->sort['name'], $sort_typs))
                {
                    $query->addOrderBy('sc.name', $this->sort['name']);
                }
            }
            
            if(isset($this->sort['categorii']))
            {
                if(in_array($this->sort['categorii'], $sort_typs))
                {
                    $query->addOrderBy('c.title', $this->sort['categorii']);
                }
            }
            
            if(isset($this->sort['order']))
            {
                if(in_array($this->sort['order'], $sort_typs))
                {
                    $query->addOrderBy('s.imageOrder', $this->sort['order']);
                }
            }
            
            if(isset($this->sort['uploadet_at']))
            {
                if(in_array($this->sort['uploadet_at'], $sort_typs))
                {
                    $query->addOrderBy('s.updateAt', $this->sort['uploadet_at']);
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
    
    
}
