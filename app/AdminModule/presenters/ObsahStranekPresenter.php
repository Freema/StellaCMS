<?php
namespace AdminModule;
/**
 * Description of ObsahStranekPresenter
 *
 * @author Tomáš Grasl
 */
class ObsahStranekPresenter extends BasePresenter {

    /**
     * @var Forms\PostForm
     */
    private $_PostForm;
    
    /**
     * @var \Models\Post\Post 
     */
    private $_Post;
    
    /**
    * @var \Models\Entity\Category 
    */
    private $_Page;
    
    final function injectPostForm(Forms\PostForm $factory)
    {
        $this->_PostForm = $factory;
    }
    
    final function injectPost(\Models\Post\Post $service)
    {
        $this->_Post = $service;
    }

    protected function createComponentPostForm()
    {
        return $this->_PostForm->createForm();
    }
    
    protected function createComponentEditPostForm()
    {
        return $this->_PostForm->createForm($this->_Page);        
    }

    public function renderDefault() {
        $this->template->tab = $this->_Post->loadPostTab();
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
    
}