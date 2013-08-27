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
    private $_type = array(
        array(
            'name'      => 'twitter_bootstrap_carosel_V3.0',
            'script'    => 'carousel.js'
            ),
    );
    
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
     * TODO'
     */
    public function loadSlideShowTab()
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
    }
}
