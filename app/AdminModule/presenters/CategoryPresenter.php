<?php
namespace AdminModule;

use Components\Paginator\PagePaginator;
/**
 * Description of CategoriePresenter
 *
 * @author Tomáš
 */
class CategoryPresenter extends BasePresenter {

    /**
     * @var Forms\CategoryForm
     */
    private $_CategoryForm;
    
    /**
     * @var  \Models\Category\Category
     */
    private $_Category;
    
    /**
     * @var \Models\Entity\Category\Category 
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
    
    final function injectCategoryForm(Forms\CategoryForm $factory)
    {
        $this->_CategoryForm = $factory;
        
        return $this;
    }
    
    final function injectCategory(\Models\Category\Category $service)
    {
        $this->_Category = $service;
        
        return $this;
    }

    protected function createComponentCategoryForm()
    {
        return $this->_CategoryForm->createForm();
    }    
    
    protected function createComponentEditCategoryForm()
    {
        return $this->_CategoryForm->createForm($this->_Page);
    }
    
    public function actionDefault($page, array $sort) {
        
        $this->_Category->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_Category->categoryItemsCount();
 
        $this->_Category->setFirstResult($paginator->getOffSet());        
        $this->_Category->setMaxResults($paginator->getMaxResults());           
        
        $this->template->tab = $this->_Category->loadCategoryTab();
    }
    
    public function actionAddCategory() {
        if($this->isAjax())
        {
            $this->setView('addCategoryAjax');
        }    
    }
    
    public function actionEditCategory($id)
    {
        if(!($this->_Page = $this->_Category->getCategoryRepository()->getOne($id)))
        {
            $this->flashMessage('Category does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;
    }
    
    public function handleDelete($id)
    {
        $this->_Category->deleteCategory($id);
        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else{
            $this->invalidateControl('categoryTable');
            $this->invalidateControl('flashMessages');
        }
    }
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }   
}