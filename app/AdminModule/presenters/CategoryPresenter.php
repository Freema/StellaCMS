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
    
    final function injectCategoryForm(Forms\CategoryForm $factory)
    {
        $this->_CategoryForm = $factory;
    }
    
    protected function createComponentCategoryForm()
    {
        return $this->_CategoryForm->createForm();
    }    
    
    public function renderDefault() {
        
    }

    public function renderAddCategory() {
        
    }

}