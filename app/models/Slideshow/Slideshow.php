<?php
namespace Components\Slideshow;
/**
 * Description of SlideShow
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityManager;
use Models\Entity\Post\SlideShowScript;
use Models\Entity\SlideShow;
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
        return $this->_em->getRepository('Models\Entity\SlideShow');
    }    
    
    /**
     * Entity mager for slide_show_script taleble. 
     * @return SlideShowScript
     */
    public function getSlideShowScriptRepository()
    {
        return $this->_em->getRepository('Models\Entity\SlideShowScript');
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
        $query->from('Models\Entity\SlideShow', 's');
        
        return $query->getQuery()->getSingleScalarResult();
    }        
    
    /**
     * @return SlideShow
     */
    public function loadSlideShowTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('s.id, s.file, s.imageOrder,sc.id as scriptId ,s.updateAt, sc.name AS script, c.title AS category');
        $query->from('Models\Entity\SlideShow', 's');
        $query->leftJoin('s.script', 'sc');
        $query->leftJoin('sc.category', 'c');
        
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
            
            if(isset($this->sort['script']))
            {
                if(in_array($this->sort['script'], $sort_typs))
                {
                    $query->addOrderBy('sc.name', $this->sort['script']);
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
    
    /**
     * @param \Nette\ArrayHash $value
     * @param array $post
     */
    final function insertNewSlideShow(\Nette\ArrayHash $value, array $post)
    {
        $script = new \Models\Entity\SlideShowScript($value->slide_show_name,'');

        $script->setOptions($this->type[$value->slide_show_script]);
        $script->setDescription($value->slide_show_desc);
        $script->setScriptId($value->slide_show_script);
        if($value->offsetExists('slide_show_category'))
        {
            $category = $this->_em->getRepository('Models\Entity\ImageCategory')
                                  ->findOneBy(array('id' => $value->slide_show_category));
            $script->setCategory($category);
        }
        
        $this->_em->persist($script);

        foreach ($post['slide_show_file'] as $key => $file)
        {
            $slide = new \Models\Entity\SlideShow($file['file']);
            $slide->setImageOrder($key);
            $slide->setScript($script);
                    
            $this->_em->persist($slide);     
        }
        $this->_em->flush();        
    }
    
    /**
     * @param \Models\Entity\SlideShowScript $slideShow
     * @param \Nette\ArrayHash $value
     * @param array $post
     */
    final function updateSlideShow(\Models\Entity\SlideShowScript $slideShow, \Nette\ArrayHash $value, array $post)
    {
        if($value->offsetExists('slide_show_category'))
        {
            $category = $this->_em->getRepository('Models\Entity\ImageCategory')
                                  ->findOneBy(array('id' => $value->slide_show_category));
            $slideShow->setCategory($category);
        }
        else
        {
            $slideShow->removeCategory();
        }
        
        $slideShow->setName($value->slide_show_name);
        $slideShow->setOptions($this->type[$value->slide_show_script]);        
        $slideShow->setDescription($value->slide_show_desc);
        
        $oldItems = array();
        $newItems = array();
        $order = 1;
        /* @var $slide SlideShow */
        foreach($slideShow->getSlideShow() as $slide)
        {
            $oldItems[] = $slide->getId();
        }

        foreach($post['slide_show_file'] as $slide_show_file)
        {
            if(isset($slide_show_file['id']))
            {
                /* @var $slideShowImageUpdate SlideShow */
                $slideShowImageUpdate = $this->getSlideShowRepository()->findOneBy(array('id' => $slide_show_file['id']));
                $slideShowImageUpdate->setImageOrder($order);
                $this->_em->merge($slideShowImageUpdate);
                
                $newItems[] = $slide_show_file['id'];                
                $order = $order + 1;
            }
            if(isset($slide_show_file['name']))
            {
                $slideShowImageNew = new \Models\Entity\SlideShow($slide_show_file['name']);
                $slideShowImageNew->setImageOrder($order);
                $slideShowImageNew->setScript($slideShow);
                $this->_em->persist($slideShowImageNew);
                
                $newItems[] = $slide_show_file['name'];
                $order = $order + 1;
            }
        }
        
        $itemsDif = \AdminModule\Forms\BaseForm::FormItemsDif($oldItems, $newItems); 
 
        if(isset($itemsDif['remove']))
        {
            foreach($itemsDif['remove'] as $deleteId)
            {
                $slideShowImageDelete = $this->getSlideShowRepository()->findOneBy(array('id' => $deleteId));
                $this->_em->remove($slideShowImageDelete);
            }
        }
        
        $this->_em->flush();
    }
    
    public function updateSlideShowImage(\Models\Entity\SlideShow $slideShow, \Nette\ArrayHash $value)
    {
        $slideShow->setName($value->slide_show_image_name);
        $slideShow->setTitle($value->slide_show_image_title);
        
        $this->_em->flush($slideShow);
    }
}
