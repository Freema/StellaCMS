<?php
namespace Models\Entity\Image; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of ImageRepository
 *
 * @author Tomáš Grasl
 */

class ImageRepository extends EntityRepository  {
    
    /**
    * @param integer $id
    * @return mixed
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
    
}