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

    public function renderDefault() {
        $this->template->tab = $this->_Post->loadPostTab();
    }

    public function renderAddArticle() {
        
    }

}