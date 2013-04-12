<?php
namespace AdminModule;
/**
 * Description of MenuPresenter
 *
 * @author Tomáš
 */
class MenuPresenter extends BasePresenter {

    /**
     * @var Forms\MenuForm
     */
    private $_MenuForm;
    
    final function injectMenuForm(Forms\MenuForm $factory)
    {
        $this->_MenuForm = $factory;
    }
    
    protected function createComponentMenuForm()
    {
        return $this->_MenuForm->createForm();
    }
    
    public function renderDefault() {
        
    }

    public function renderAddMenu() {
        
    }

}