<?php
namespace Models\Entity\Post; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of SlideShowRepository
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class SlideShowRepository extends EntityRepository  {
    
    /**
    * @param integer $id
    * @return mixed
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
}
