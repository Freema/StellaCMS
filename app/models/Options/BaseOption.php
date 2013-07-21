<?php
namespace Models\Omptions;

use Doctrine\ORM\EntityManager;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;

/**
 * Description of Base option abstract class
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
abstract class BaseOption {
    
    /** @var string */
    protected $cash_name = 'Options';

    /** @var EntityManager */   
    protected $_em;
    
    /** @var Cache */
    protected $cache;
    
    function __construct(EntityManager $em,FileStorage $cacheStoreage) {
        $this->_em = $em;  
        $this->cache = new Cache($cacheStoreage, $this->cash_name);
    }
    
    public function getOptionRepository()
    {
        return $this->_em->getRepository('Models\Entity\Options\Option');
    }
}
