<?php
namespace AdminModule;

use AdminModule\Forms\OpenGraphForm;
use AdminModule\Forms\PageForm;
use Models\Omptions\Facebook;
use Models\Omptions\Page;

/**
 * Description of OptionsPresenter
 *
 * @author Tomáš
 */
class OptionsPresenter extends BasePresenter {

    /**
     * @var OpenGraphForm
     */
    protected $_OpenGraphForm;

    /** @var PageForm */
    protected $_PageForm;
    
    /** @var Facebook */
    protected $_FbService;

    /** @var Page */
    protected $_PageService;    
    
    final function injectEntityFacebookService(Facebook $service) {
        $this->_FbService = $service;                
    }
    
    final function injectOpenGraphForm(OpenGraphForm $form)
    {
        $this->_OpenGraphForm = $form;
    }
    
    final function injectPageForm(PageForm $form)
    {
        $this->_PageForm = $form;
    }
    
    final function injectPageOptionsService(Page $service)
    {
        $this->_PageService = $service;
    }
    
    
    public function renderDefault() {
        
    }
    
    protected function createComponentOpenGraphForm()
    {
        return $this->_OpenGraphForm->createForm($this->_FbService->getFacebookOptions());
    }
    
    protected function createComponentPageForm()
    {
        return $this->_PageForm->createForm($this->_PageService->getPageOptions());
    }

}