<?php
namespace AdminModule;
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
    * @var \Models\Entity\Tag\Tag 
    */
    private $_Page;
    
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

    public function renderDefault() {
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
        $this->_Tag->deleteTag($id);
        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else{
            $this->invalidateControl('tagTable');
            $this->invalidateControl('flashMessages');
        }
    }  
}