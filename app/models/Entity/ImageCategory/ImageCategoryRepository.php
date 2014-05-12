<?php
namespace Models\Entity; 
use Doctrine\ORM\EntityRepository;
/**
 * Description of ImageCategoryRepository
 *
 * @author Tomáš
 */

class ImageCategoryRepository extends EntityRepository  {
    
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
    public function getImageCategories()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
    
}
