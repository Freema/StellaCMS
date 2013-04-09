<?php
namespace Models\Entity\Category; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

class CategoryRepository extends EntityRepository  {
    
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
    
}
