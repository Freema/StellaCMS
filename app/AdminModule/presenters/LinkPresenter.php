<?php
namespace AdminModule;
/**
 * Description of LinkPresenter
 *
 * @author Tomáš
 */
class LinkPresenter extends BasePresenter {

    /**
     * @var Forms\LinkForm
     */
    private $_LinkForm;
    
    /**
     * @var \Models\Link\Link  
     */
    private $_Link;
    
    /**
    * @var \Models\Entity\Link\Link 
    */
    private $_Page;
    
    public function injectLinkForm(Forms\LinkForm $factory)
    {
        $this->_LinkForm = $factory;
    }
    
    public function injectLink(\Models\Link\Link $service)
    {
        $this->_Link = $service;
    }
    
    protected function createComponentLinkForm()
    {
        return $this->_LinkForm->createForm();
    }
    
    protected function createComponentEditLinkForm()
    {
        return $this->_LinkForm->createForm($this->_Page);
    }

    public function renderDefault() {
        $this->template->tab = $this->_Link->loadLinkTab();        
    }

    public function actionEditLink($id) {

        if(!($this->_Page = $this->_Link->getLinkRepository()->getOne($id)))
        {
            $this->flashMessage('Link does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;
    }
}