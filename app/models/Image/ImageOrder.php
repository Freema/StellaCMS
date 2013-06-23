<?php
namespace Models\Image;
/**
 * Description of ImagesOrder
 *
 * @author Tomáš
 */

use Nette\Object;
use Doctrine\ORM\EntityManager;

abstract class ImageOrder extends Object {
    
    /** @var EntityManager */    
    protected $_em;
   
    /** @var string */
    protected $_entity;

    /**
     * @param $dir
     */
    public function __construct(EntityManager $em) {
        $this->_em = $em;
    }
    
    protected function setEntity($name)
    {
        $this->_entity = $name;
    }
    
    /**
     * 
     * SELECT MAX(imageOrder) AS order 
     * FROM [table] 
     * WHERE category_id IS NULL 
     * 
     * @param integer $where
     */
    protected function lastOrder($where = NULL)
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('MAX(o.imageOrder)');
        $query->from($this->_entity, 'o');
        
        if(isset($where))
        {
            $query->where('o.category = :category');
            $query->setParameter(':category', $where);
        }
        else
        {
            $query->where('o.category IS NULL');
        }

        return $query->getQuery()->getSingleResult();
    }

    /**
     *  UPDATE [table]
     *  SET imageOrder = imageOrder - 1 
     *  WHERE id !=  %i AND category_id = %i AND imageOrder > %i',
     *  $id, $category, $order)
     * 
     * @param integer $id
     * @param integer $category
     * @param integer $order
     */
    protected function updateTheOrderAfterDeleting($id, $category, $order)
    {
        $query = $this->_em->createQueryBuilder();
        $query->update($this->_entity, 'o');
        $query->set('o.imageOrder', 'o.imageOrder - 1');
        $query->where('o.id != :id')->setParameter('id', $id);
        
        if(isset($category))
        {
            $query->andWhere('o.category = :category')->setParameter(':category', $category->getId());
        }
        else
        {
            $query->andWhere('o.category IS NULL');
        }
        
        $query->andWhere('o.imageOrder > :order')->setParameter('order', $order);
        
        $query->getQuery()->getResult();
    } 
    
    /**
     * query1:
     * UPDATE [table] 
     * SET imageOrder = imageOrder - 1 
     * WHERE id != %i AND category_id = %i AND imageOrder > %i',
     * $id, $category, $order
     * 
     * query2:
     * UPDATE [table] 
     * SET imageOrder = imageOrder + 1
     * WHERE id != %i AND category_id = %i AND imageOrder >= %i',
     *  
     * @param type $id
     * @param type $category
     * @param type $order
     * @param type $newOrder
     */
    protected function updateTheOrderAfterImageUpdate($id, $category, $order, $newOrder)
    {
        $this->updateTheOrderAfterDeleting($id, $category, $order);
        
        $query = $this->_em->createQueryBuilder();
        $query->update($this->_entity, 'o');
        $query->set('o.imageOrder', 'o.imageOrder + 1');
        $query->where('o.id != :id')->setParameter('id', $id);
        
        if(isset($category))
        {
            $query->andWhere('o.category = :category')->setParameter(':category', $category->getId());
        }
        else
        {
            $query->andWhere('o.category IS NULL');
        }
        
        $query->andWhere('o.imageOrder >= :order')->setParameter('order', $newOrder);
        
        $query->getQuery()->getResult();        
    }
    
}

