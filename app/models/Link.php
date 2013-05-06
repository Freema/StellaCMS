<?php
namespace Models\Link;

use Doctrine\ORM\EntityManager;
use Nette\Object;

/** 
 * Description of Link
 *
 * @author Tomáš Grasl
 */
class Link extends Object {
    
    /** @var EntityManager */    
    private $_em;
    
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;        
    }
    
    /**
     * @return Models\Entity\Link\Link
     */
    public function getLinkRepository()
    {
        return $this->_em->getRepository('Models\Entity\Link\Link');
    }

    public function loadLinkTab()
    {
        $query = $this->_em->createQuery('SELECT l.id, l.title, l.url, l.description, l.createdAt
                                          FROM Models\Entity\Link\Link l');
        
        return $query->getResult();
    }
    
    public function deleteLink($id)
    {
        $link = $this->getLinkRepository()->getOne($id);
        $this->_em->remove($link);
        return $this->_em->flush();
    }
}
