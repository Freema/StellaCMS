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
    
    final function injectCategoryForm(Forms\CategoryForm $factory)
    {
        $this->_CategoryForm = $factory;
        
        return $this;
    }
    
    final function injectCategory(\Models\Category\Category $factory)
    {
        $this->_Category = $factory;
        
        return $this;
    }

    protected function createComponentCategoryForm()
    {
        return $this->_CategoryForm->createForm();
    }    
    
    public function renderDefault() {
        $this->template->tab = $this->_Category->loadCategoryTab();
    }

    public function renderAddCategory() {
        
    }

}