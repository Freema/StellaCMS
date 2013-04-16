<?php
namespace Models\Post;

use Doctrine\ORM\EntityManager;
use Nette\Object;
/**
 * Description of Article
 *
 * @author Tomáš Grasl
 */
class Post extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    public function getPostRepository()
    {
        return $this->_em->getRepository('Models\Entity\Post\Post');
    }

    public function loadPostTab()
    {
        return $this->getPostRepository()->findAll();
    }
    
    public function deleteArticle()
    {
        
    }    
    
    
}
