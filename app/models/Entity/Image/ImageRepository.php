<?php
namespace Models\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Models\Entity\ImageCategory;
/**
 * Description of ImageRepository
 *
 * @author Tomáš Grasl
 */

class ImageRepository extends EntityRepository  {
    
   /**
    * take one item from DB
    * 
    * @param integer $id
    * @return /Image
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
    
    /**
     * get Image order query
     * 
     * @param mix $category
     * @return array ID, imageOrder column
     */
    public function getImageOrder($category)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('i.id, i.imageOrder')
                      ->from('Models\Entity\Image', 'i');

        if($category instanceof ImageCategory)
        {
            $query->where('i.category = :category')
                  ->orderBy('i.imageOrder')
                  ->setParameter(':category', $category);
        }
        else
        {
            $query->where('i.category IS NULL')
                  ->orderBy('i.imageOrder');
        }
        
        return $query->getQuery()->getResult();
    }
    
    public function findAllImageName()
    {
        $query = $this  ->getEntityManager()
                        ->createQueryBuilder()
                        ->select('i.name')
                        ->from('Models\Entity\Image', 'i');
        
        return $query->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
    
}