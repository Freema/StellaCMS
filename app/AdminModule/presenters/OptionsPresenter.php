<?php
namespace AdminModule;

use AdminModule\Forms\OpenGraphForm;

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
    
    final function injectOpenGraphForm(OpenGraphForm $form)
    {
        $this->_OpenGraphForm = $form;
    }
    
    public function renderDefault() {
        
    }
    
    protected function createComponentOpenGraphForm()
    {
        return $this->_OpenGraphForm->createForm();
    }

}