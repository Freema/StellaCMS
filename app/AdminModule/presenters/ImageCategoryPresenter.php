<?php
namespace AdminModule;
use Components\Paginator\PagePaginator;
use Models\Image\ImageCategory;
/**
 * Description of ImageCategory
 *
 * @author Tomáš Grasl
 */
class ImageCategoryPresenter extends BasePresenter {

    /**
     * @var Forms\ImageCategoryForm
     */
    private $_ImageCategoryForm;
    
    /**
     * @var  ImageCategory
     */
    private $_ImageCategory;
    
    /**
     * @var \Models\Entity\ImageCategory\ImageCategory
     */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'id'            => 'NONE',
        'title'         => 'NONE',
        'slug'          => 'NONE',
        'images'        => 'NONE',
    );      
    
    final function injectCategoryForm(Forms\ImageCategoryForm $factory)
    {
        $this->_ImageCategoryForm = $factory;
        
        return $this;
    }
    
    final function injectCategory(ImageCategory $service)
    {
        $this->_ImageCategory = $service;
        
        return $this;
    }

    protected function createComponentImageCategoryForm()
    {
        return $this->_ImageCategoryForm->createForm();
    }    
    
    protected function createComponentEditImageCategoryForm()
    {
        return $this->_ImageCategoryForm->createForm($this->_Page);
    }
    
    public function actionDefault($page, array $sort) {
        
        $this->_ImageCategory->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_ImageCategory->categoryItemsCount();
 
        $this->_ImageCategory->setFirstResult($paginator->getOffSet());        
        $this->_ImageCategory->setMaxResults($paginator->getMaxResults());          
        
        $this->template->tab = $this->_ImageCategory->loadImageCategoryTab();
    }
    
    public function actionAddCategory() {
        if($this->isAjax())
        {
            $this->setView('addCategoryAjax');
        }    
    }    
    
    public function actionEditCategory($id)
    {
        if(!($this->_Page = $this->_ImageCategory->getImageCategoryRepository()->getOne($id)))
        {
            $this->flashMessage('Category does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;
    }
    
    public function handleDelete($id)
    {
        $this->_ImageCategory->deleteCategory($id);
        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else{
            $this->invalidateControl('mediaCategoryTable');
            $this->invalidateControl('flashMessages');
        }
    }
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }   
}