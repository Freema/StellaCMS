<?php
namespace Models\Omptions;

use Doctrine\ORM\EntityManager;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Doctrine\ORM\Query;

/**
 * Description of Base option abstract class
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
abstract class BaseOption {
    
    /** @var string */
    protected $storage_name = 'Options';

    /** @var EntityManager */   
    protected $_em;
    
    /** @var Cache */
    protected $cache;
    
    /** @var Query::HYDRATE_ARRAY */
    public $hydrate_mode = Query::HYDRATE_ARRAY;
    
    
    function __construct(EntityManager $em,FileStorage $cacheStoreage) {
        $this->_em = $em;  
        $this->cache = new Cache($cacheStoreage, $this->storage_name);
    }
    
    public function getOptionRepository()
    {
        return $this->_em->getRepository('Models\Entity\Options\Option');
    }
}
