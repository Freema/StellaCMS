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
    
    /**
     * @return Models\Entity\Post\Post
     */
    public function getPostRepository()
    {
        return $this->_em->getRepository('Models\Entity\Post\Post');
    }

    public function loadPostTab(array $where = NULL)
    {
        if(isset($where))
        {
            $query = $this->getPostRepository()->findBy($where);
        }
        else
        {
            $query = $this->getPostRepository()->findAll();
        }
        
        return $query;        
    }
    
    public function deleteArticle($id)
    {
        $category = $this->getPostRepository()->getOne($id);
        $this->_em->remove($category);
        return $this->_em->flush();
    }        
}
