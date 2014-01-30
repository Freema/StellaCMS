<?php
namespace Models\Entity\Category;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoryRepository
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
    public function getCategories()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
    
    public function getRoutList()
    {
        $query = $this  ->createQueryBuilder("Category")
                        ->select("Category.id, Category.slug")
                        ->getQuery()
                        ->getResult();
        
        $return = array();
        
        foreach ($query as $value)
        {
            $return[$value['id']] = $value['slug'];
        }
            
        return $return;
    }
}
