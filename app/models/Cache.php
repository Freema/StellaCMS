<?php
namespace Models;

/**
 * Description of Cache
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class Cache {
   
    /**
     * @var Nette\Caching\Storages\FileStorage
     */
    private $_cacheStorage;
    
    /**
     * @var string
     */
    private $_key;
    
    public function __construct(Nette\Caching\Storages\FileStorage $cacheStoreage)
    {
        $this->_cacheStorage = $cacheStoreage;
    }
    
    
    
    
    
}
