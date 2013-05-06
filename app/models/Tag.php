<?php
namespace Models\Tag;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of Tag
 *
 * @author Tomáš Grasl
 */
class Tag extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    /**
     * @return \Models\Entity\Tag\Tag
     */
    public function getTagRepository()
    {
        return $this->_em->getRepository('Models\Entity\Tag\Tag');
    }

    public function loadTagTab()
    {
        $query = $this->_em->createQuery('SELECT t.id, t.name, t.slug, t.description
                                          FROM Models\Entity\Tag\Tag t');
        
        return $query->getResult();
    }
    
    public function deleteTag($id)
    {
        $tag = $this->getTagRepository()->getOne($id);
        $this->_em->remove($tag);
        return $this->_em->flush();
    }
}
