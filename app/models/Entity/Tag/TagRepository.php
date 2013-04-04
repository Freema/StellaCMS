<?php

use Doctrine\ORM\EntityRepository;

/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

class TagRepository extends EntityRepository  {
    
    
    public function findByName()
    {
        return 'suxx';
    }
    
}

