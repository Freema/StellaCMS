<?php
namespace Models\Entity\Tag; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */
class TagRepository extends EntityRepository  {
   
    /**
    * @param integer $id
    * @return mixed
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
    
    /**
     * @return array
     */
    public function getTags()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }    
}

