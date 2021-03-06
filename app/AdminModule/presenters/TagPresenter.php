<?php
namespace AdminModule;

use Components\Paginator\PagePaginator;
/**
 * Description of TagPresenter
 *
 * @author Tomáš Grasl
 */
class TagPresenter extends BasePresenter {

    /**
     * @var Forms\TagForm
     */
    private $_TagForm;
    
    /**
     * @var \Models\Tag\Tag  
     */
    private $_Tag;
    
    /**
    * @var \Models\Entity\Tag 
    */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'id'            => 'NONE',
        'title'         => 'NONE',
        'slug'          => 'NONE',
        'posts'         => 'NONE',
    );    
    
    public function injectLinkForm(Forms\TagForm $factory)
    {
        $this->_TagForm = $factory;
    }
    
    public function injectLink(\Models\Tag\Tag $service)
    {
        $this->_Tag = $service;
    }
    
    protected function createComponentTagForm()
    {
        return $this->_TagForm->createForm();
    }
    
    protected function createComponentEditTagForm()
    {
        return $this->_TagForm->createForm($this->_Page);
    }

    public function actionDefault($page, array $sort) {
        
        $this->_Tag->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_Tag->tagItemsCount();
 
        $this->_Tag->setFirstResult($paginator->getOffSet());        
        $this->_Tag->setMaxResults($paginator->getMaxResults());        
        
        $this->template->tab = $this->_Tag->loadTagTab();        
    }

    public function actionEditTag($id) {

        if(!($this->_Page = $this->_Tag->getTagRepository()->getOne($id)))
        {
            $this->flashMessage('Tag does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;
    }
    
    public function handleDelete($id)
    {
        $delete = $this->_Tag->deleteTag($id);
        if($delete)
        {
            $this->flashMessage('Štítek byl vymazán!', 'success');
        }
        else
        {
            $this->flashMessage('Nastala chyba při mazání štítku!', 'error');
        }
        
        $this->redirect('this');
    }
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }     
}