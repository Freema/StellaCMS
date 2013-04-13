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

    public function loadCategoryTab()
    {
        $query = $this->_em->createQuery('SELECT c.id, c.title, c.slug, c.description, COUNT(p.category) AS posts
                                          FROM Models\Entity\Category\Category c
                                          LEFT JOIN c.posts p
                                          GROUP BY c.id');
        
        return $query->getResult();
    }
    
}