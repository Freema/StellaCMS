<?php
namespace Models\Image;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of ImageCategory
 *
 * @author Tomáš Grasl
 */
class ImageCategory extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    /**
     * @return Models\Entity\ImageCategory\ImageCategory
     */
    public function getImageCategoryRepository()
    {
        return $this->_em->getRepository('Models\Entity\ImageCategory\ImageCategory');
    }

    public function loadImageCategoryTab()
    {
        $query = $this->_em->createQuery('SELECT c.id, c.title, c.slug, c.description, COUNT(i.category) AS images
                                          FROM Models\Entity\ImageCategory\ImageCategory c
                                          LEFT JOIN c.image i
                                          GROUP BY c.id');
        
        return $query->getResult();
    }
    
    public function deleteCategory($id)
    {
        $category = $this->getImageCategoryRepository()->getOne($id);
        $this->_em->remove($category);
        return $this->_em->flush();
    }
}
