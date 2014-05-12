<?php
namespace Models\Entity; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of TagRepository
 *
 * @author Tomáš Grasl
 */

class LinkRepository extends EntityRepository  {
    
    /**
    * @param integer $id
    * @return mixed
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
    
}