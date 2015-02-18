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

    /** @var PageForm */
    protected $_PageForm;
    
    /** @var Facebook */
    protected $_FbService;

    /** @var Page */
    protected $_PageService;    
    
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
    
    protected function createComponentPageForm()
    {
        return $this->_PageForm->createForm($this->_PageService->getPageOptions());
    }

}