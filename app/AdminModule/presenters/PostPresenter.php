<?php
namespace AdminModule;

use Components\Paginator\PagePaginator;
/**
 * Description of ObsahStranekPresenter
 *
 * @author Tomáš Grasl
 */
class PostPresenter extends BasePresenter {

    /**
     * @var Forms\PostForm
     */
    private $_PostForm;
    
    /**
     * @var \Models\Post\Post 
     */
    private $_Post;
    
    /**
     * @var \Models\Category\Category 
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
        'author'        => 'NONE',
        'categorii'     => 'NONE',
        'public'        => 'NONE',
        'uploadet_at'   => 'NONE',
        );
    
    final function injectPostForm(Forms\PostForm $factory)
    {
        $this->_PostForm = $factory;
    }
    
    final function injectPost(\Models\Post\Post $service)
    {
        $this->_Post = $service;
    }
    
    final function injectCategory(\Models\Category\Category $service)
    {
        $this->_Category = $service;
    }

    protected function createComponentPostForm()
    {
        return $this->_PostForm->createForm();
    }
    
    protected function createComponentEditPostForm()
    {
        return $this->_PostForm->createForm($this->_Page);        
    }

    public function actionDefault($page, array $sort, $category)
    {
        $cFilter = $this->_PostForm->prepareForFormItem(
                $this->_Category->getCategoryRepository()->getCategories(),
                'title', 
                TRUE);
        
        if(!is_null($category))
        {
            $cf_test = $this->_checkCategoryExist($category, $cFilter);
            dump($cf_test);
            $this->_Post->setFilter($cf_test);
        }
        
        $this->_Post->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_Post->postItemsCount();
 
        $this->_Post->setFirstResult($paginator->getOffSet());        
        $this->_Post->setMaxResults($paginator->getMaxResults());
        
        $this->template->tab = $this->_Post->loadPostTab();

        $this->template->cFilter = $cFilter;
    }    
    
    /**
     * @param int $id
     * @param array $category
     * @return integer | null
     */
    private function _checkCategoryExist($id, array $category)
    {
        if(array_key_exists($id, $category))
        {
            return $id;
        }
        else
        {
            return NULL;
        }
    }

    public function actionEditArticle($id)
    {
        if(!($this->_Page = $this->_Post->getPostRepository()->getOne($id)))
        {
            $this->flashMessage('Post does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;
    }
    
    public function handleDelete($id)
    {
        $this->_Post->deleteArticle($id);
        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else{
            $this->invalidateControl('articleTable');
            $this->invalidateControl('flashMessages');
        }
    }
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }       
    
}