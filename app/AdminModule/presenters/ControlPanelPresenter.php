<?php
namespace AdminModule;

/**
 * Description of ControlPanelPresenter
 *
 * @author Tomáš
 */
class ControlPanelPresenter extends BasePresenter {

    
    public function renderDefault() {
        
        $b = new \Components\Breadcrumbs\Breadcrumbs();
        $b->create($this->getAction(TRUE));
        
    }

}