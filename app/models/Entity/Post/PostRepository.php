<?php
namespace Models\Entity\Post; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of TagRepository
 *
 * @author Tomáš Grasl
 */

class PostRepository extends EntityRepository  {
    
    /**
    * @param integer $id
    * @return mixed
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
}
