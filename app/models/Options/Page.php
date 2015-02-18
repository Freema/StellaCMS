<?php
namespace Models\Omptions;

/**
 * Description of Page options
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityManager;
use Models\Entity\OptionRepository;
use Nette\Caching\Storages\FileStorage;

class Page extends BaseOption {

    /** @var array */
    protected $_pageOptions;

    /** @var string */
    protected $storage_name = 'Options.Page';
    
    /** @var string */
    protected $cash_name = 'Page';
    
    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Nette\Caching\Storages\FileStorage $storage
     */
    function __construct(EntityManager $em,  FileStorage $storage) {
        parent::__construct($em, $storage);
    }
    
    /**
     * @return object
     */
    public function getPageControl()
    {
        $value = $this->cache->load($this->cash_name);
        
        if($value === NULL)
        {
            if($this->getOptionRepository()->CheckPageOptionsIsActive())
            {
                $query = $this->getPageOptions();
                $this->PageCache($query);
            }
            else
            {
                return $this->_pageOptions;
            }
        }
        else
        {
            foreach($value as $tag)
            {
                if(!empty($tag['option_value']))
                {
                        $this->_pageOptions[$tag['option_name']] = $tag['option_value'];
                }
            }            
        }
        return $this->_pageOptions;
    }
    
    /**
     * @return array | object
     */
    public function getPageOptions()
    {
        $query = $this->_em
                     ->createQuery('
                    SELECT o 
                    FROM Models\Entity\Option o 
                    WHERE o.option_name LIKE ?1
                    ')
                    ->setParameter(1, OptionRepository::PAGE_PREFIX.'%')
                    ->getResult($this->hydrate_mode);
        
        return $query;        
    }
    
    /**
     * @param integer $activation
     */
    public function PageCacheUnset($activation)
    {
        if(!(bool)$activation)
        {
            if($this->cache->load($this->cash_name))
            {
                $this->cache->save($this->cash_name, NULL);
            }
        }
    }

    /**
     * @param array $query
     */
    protected function PageCache($query)
    {
        foreach($query as $tag)
        {
            if($tag['option_name'] === OptionRepository::PAGE_PREFIX.'_Cash')
            {
                $cash = (bool) $tag['option_value'];
            }
            
            if(!empty($tag['option_value']))
            {
                    $this->_pageOptions[$tag['option_name']] = $tag['option_value'];
            }
        }

        if($cash)
        {
            $this->cache->save($this->cash_name, $query);
        }        
    }

    /**
     * @param array $data
     */
    public function updatePagedata($data)
    {
        foreach ($data as $key => $value)
        {
            /* @var $record \Models\Entity\Option */
            $record = $this->getOptionRepository()->findOneBy(array('option_name' => $key));
            $record->setOptionValue($value);
        }
        $this->PageCacheUnset(false);     
        $this->_em->flush();
    }
}