<?php

namespace Components\Paginator;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;


/**
 * Description of Paginator
 * @author Tomáš Grasl
 * 
 */

class PagePaginator extends Control {
    
    /** @var Paginator */
    public $paginator;

    /**
     * @var integer 
     */
    private $itemsPerPage = 15;
    
    /**
     * @var integer 
     */
    public $page;
    
    /**
     * @var integer
     */
    public $itemCount;
    
    /**
     * @param integer $current
     */
    public function setItemsPerPage($current)
    {
        $this->itemsPerPage = (int) $current;  
    }
    
    /**
     * @return integer
     */
    public function getOffset()
    {
        if (!$this->paginator) {
            $offSet = $this->getPaginator()->getOffset();
        }
        else
        {
            $offSet = $this->paginator->getOffset();
        }
        
        return $offSet;
    }
    
    public function getMaxResults()
    {
        return $this->paginator->getItemsPerPage();
    }         
    
    public function render()
    {
        $paginator = $this->paginator;
        $page = $paginator->page;
        $pages = $paginator->pageCount;
        
        $links = array();

        $min = max($page - 2, 2);
        $max = min($page + 2, $pages-1);
        $links[] = "1";

        if ($min > 2)
        {
            $links[] = "...";
        }

        for ($i=$min; $i<$max+1; $i++)
        {
            $links[] = "$i";
        }

        if ($max < $pages-1)
        {
            $links[] = "...";
        }
        $links[] = "$pages";

        $this->template->pages = $pages;
        $this->template->page = $page;
        $this->template->links = $links;
        $this->template->firstPage = $paginator->firstPage;
        $this->template->lastPage = $paginator->lastPage;
        
        $this->template->setFile(__DIR__.'/Template.latte');
        $this->template->render();
    }
    
    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        $paginator = new Paginator;
        
        $session = $this->getPresenter()->getSession()->getSection('pagination');
        
        if(!$session->pagination == NULL)
        {
            $this->setItemsPerPage($session->pagination);
        }        
        
        $paginator->page = $this->page;
        $paginator->itemCount = $this->itemCount;
        $paginator->itemsPerPage = $this->itemsPerPage;
        return $this->paginator = $paginator;
    }
    
    public function handlePageNumbers($number)
    {
        $session = $this->getPresenter()->getSession()->getSection('pagination');
        $session->pagination = $number;
        
        $this->redirect('this');
    }    
}
