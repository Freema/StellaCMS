<?php
namespace Models\Entity\Category; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

class CategoryRepository extends EntityRepository  {
    
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
    public function getLastCategories()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
    
}
