<?php
namespace Models\Comment;

use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Form;
use Nette\Object;
use Models\Entity\Post\Post;
/**
 * Description of Comment
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class Comment extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    /** @var \Nette\Http\Request */
    private $_request;
    
    /** @var array */
    protected $filter;
    
    /** @var array */
    protected $sort;
    
    /** @var integer */
    protected $page;
    
    /** @var integer */
    protected $maxResults;
    
    /** @var integer */
    protected $firstResult;    
    
    public function __construct(EntityManager $em, \Nette\Http\Request $request)
    {
        $this->_request = $request;
        $this->_em = $em;        
    }

    /**
     * @param array $sort
     * @return Comment
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;
        return $this;
    }
    
    /**
     * @param array $filter
     * @return Comment
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }
    
    /**
     * @param integer $page
     * @return Comment
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
        return $this;
    }
    
    /**
     * @param integer $result
     * @return Comment 
     */
    public function setMaxResults($result)
    {
        $this->maxResults = (int) $result;
        return $this;
    }
    
    /**
     * @param integer $result
     * @return Comment 
     */
    public function setFirstResult($result)
    {
        $this->firstResult = (int) $result;
        return $this;
    }
    
    /**
     * @return Models\Entity\Comment\Comment
     */
    public function getCommentRepository()
    {
        return $this->_em->getRepository('Models\Entity\Comment\Comment');
    }
    
    /**
     * @return integer
     */
    public function postItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(p.id)');
        $query->from('Models\Entity\Post\Post', 'p');
        
        return $query->getQuery()->getSingleScalarResult();
    }
    
    public function loadCommetTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('c');
        $query->from('Models\Entity\Comment\Comment', 'c');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('p.id', $this->sort['id']);
                }
            }
            
            if(isset($this->sort['title']))
            {
                if(in_array($this->sort['title'], $sort_typs))
                {
                    $query->addOrderBy('p.title', $this->sort['title']);
                }
            }
            
            if(isset($this->sort['author']))
            {
                if(in_array($this->sort['author'], $sort_typs))
                {
                    $query->addOrderBy('u.username', $this->sort['author']);
                }
            }
            
            if(isset($this->sort['categorii']))
            {
                if(in_array($this->sort['categorii'], $sort_typs))
                {
                    $query->addOrderBy('c.title', $this->sort['categorii']);
                }
            }
            
            if(isset($this->sort['public']))
            {
                if(in_array($this->sort['public'], $sort_typs))
                {
                    $query->addOrderBy('p.publish', $this->sort['public']);
                }
            }
            
            if(isset($this->sort['uploadet_at']))
            {
                if(in_array($this->sort['uploadet_at'], $sort_typs))
                {
                    $query->addOrderBy('p.createdAt', $this->sort['uploadet_at']);
                }
            }            
        }
        
        if(!empty($this->filter))
        {
            $query->where('c.post_id = ?1');
            $query->setParameter(1, $this->filter);
        }
        
        if(!empty($this->maxResults) && !empty($this->firstResult))
        {
            $query->setMaxResults($this->maxResults);
            $query->setFirstResult($this->firstResult);
        }
        
        return $query->getQuery()->getResult();
    }
    
    public function insertNewComment(Form $form,Post $post)
    {
        $values = $form->getValues();
        
        $comment = new \Models\Entity\Comment\Comment();
        $comment->setTitle($values->comment_title);
        $comment->setUser($values->comment_user);
        if($values->offsetExists('comment_email'))
        {
            $comment->setEmailAddress($values->comment_email);
        }
        $comment->setContent($values->comment_message);
        $comment->setIpAdress($this->_request->getRemoteAddress());
        $comment->setPost($post);
        $comment->setApprove(TRUE);
        
        
        $this->_em->persist($comment);
        $this->_em->flush();
    }
    
    
    
    public function deleteArticle($id)
    {
    }        
}
