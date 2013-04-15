<?php
namespace Models\Category;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of Category
 *
 * @author Tomáš
 */
class Category extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    public function getCategoryRepository()
    {
        return $this->_em->getRepository('Models\Entity\Category\Category');
    }

    public function loadCategoryTab()
    {
        $query = $this->_em->createQuery('SELECT c.id, c.title, c.slug, c.description, COUNT(p.category) AS posts
                                          FROM Models\Entity\Category\Category c
                                          LEFT JOIN c.posts p
                                          GROUP BY c.id');
        
        return $query->getResult();
    }
    
    public function deleteCategory($id)
    {
        $category = $this->getCategoryRepository()->getOne($id);
        $this->_em->remove($category);
        return $this->_em->flush();
        
    }
}
