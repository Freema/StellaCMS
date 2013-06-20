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
    private $paginator;

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
    public function __construct($session) {
        parent::__construct();
        
        if (!($session instanceof Nette\Application\UI\Presenter))
        {
            throw new Nette\InvalidStateException("neni instanci presenteru Nette\Application\UI\Presenter");           
        }
        
        $this->session = $session->getSession()->getSection('pagination');
        
        if(empty($this->session->pagination))
        {
            $current = 15;
        }
        else
        {
            $current = $this->session->pagination;
        }
            
        $this->page_numbers_current = $current;  
    }
     * 
     */    
    
    /**
     * @param integer $current
     */
    public function setItemsPerPage($current)
    {
        $this->itemsPerPage = (int) $current;  
    }
    
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
        $paginator->page = $this->page;
        $paginator->itemCount = $this->itemCount;
        $paginator->itemsPerPage = $this->itemsPerPage;
        return $this->paginator = $paginator;
    }
}
