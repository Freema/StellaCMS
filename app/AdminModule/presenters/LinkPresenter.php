<?php
namespace AdminModule;

use Components\Paginator\PagePaginator;
/**
 * Description of LinkPresenter
 *
 * @author Tomáš Grasl
 */
class LinkPresenter extends BasePresenter {

    /**
     * @var Forms\LinkForm
     */
    private $_LinkForm;
    
    /**
     * @var \Models\Link\Link  
     */
    private $_Link;
    
    /**
    * @var \Models\Entity\Link 
    */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'id'            => 'NONE',
        'title'         => 'NONE',
        'slug'          => 'NONE',
        'date'          => 'NONE',
    );     
    
    public function injectLinkForm(Forms\LinkForm $factory)
    {
        $this->_LinkForm = $factory;
    }
    
    public function injectLink(\Models\Link\Link $service)
    {
        $this->_Link = $service;
    }
    
    protected function createComponentLinkForm()
    {
        return $this->_LinkForm->createForm();
    }
    
    protected function createComponentEditLinkForm()
    {
        return $this->_LinkForm->createForm($this->_Page);
    }

    public function actionDefault($page, array $sort) {
        
        $this->_Link->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_Link->linkItemsCount();
 
        $this->_Link->setFirstResult($paginator->getOffSet());        
        $this->_Link->setMaxResults($paginator->getMaxResults());         
        
        $this->template->tab = $this->_Link->loadLinkTab();        
    }

    public function actionEditLink($id) {

        if(!($this->_Page = $this->_Link->getLinkRepository()->getOne($id)))
        {
            $this->flashMessage('Link does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;
    }
    
    public function handleDelete($id)
    {
        $this->_Link->deleteLink($id);
        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else{
            $this->invalidateControl('linkTable');
            $this->invalidateControl('flashMessages');
        }
    }

    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }  
}