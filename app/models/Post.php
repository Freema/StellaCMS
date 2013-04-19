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

    public function loadPostTab()
    {
        $query = $this->_em->createQuery('SELECT p.id, p.title, u.username, c.title AS category, p.createdAt
                                          FROM Models\Entity\Post\Post p
                                          JOIN p.category c
                                          JOIN p.users u');
        
        return $query->getResult();        
    }
    
    public function loadPostTabWhere($where)
    {
        $query = $this->_em ->createQueryBuilder()
                            ->select('p.id, p.title, u.username, c.title AS category, p.createdAt')
                            ->from('Models\Entity\Post\Post', 'p')
                            ->join('p.category', 'c')
                            ->join('p.users', 'u')
                            ->where('c.id = :category')
                            ->setParameter('category', $where)
                            ->getQuery();
        
        return $query->getResult();        
    }
    
    public function deleteArticle($id)
    {
        $category = $this->getPostRepository()->getOne($id);
        $this->_em->remove($category);
        return $this->_em->flush();
    }        
}
