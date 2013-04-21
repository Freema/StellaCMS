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
    
    public function injectLinkForm(Forms\LinkForm $factory)
    {
        $this->_LinkForm = $factory;
    }
    
    protected function createComponentLinkForm()
    {
        return $this->_LinkForm->createForm();
    }

    public function renderDefault() {
        
    }

    public function renderEditLink() {
        
    }

}