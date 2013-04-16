<?php
namespace AdminModule;
/**
 * Description of CategoriePresenter
 *
 * @author Tomáš
 */
class CategoryPresenter extends BasePresenter {

    /**
     * @var Forms\PostForm
     */
    private $_CategoryForm;
    
    /**
     * @var  \Models\Category\Category
     */
    private $_Category;
    
    /**
     * @var \Models\Entity\Category 
     */
    private $_Page;
    
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
    
    public function renderDefault() {
        $this->template->tab = $this->_Category->loadCategoryTab();
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

}