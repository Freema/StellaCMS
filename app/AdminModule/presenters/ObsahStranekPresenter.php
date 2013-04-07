<?php
namespace AdminModule;
/**
 * Description of ObsahStranekPresenter
 *
 * @author Tomáš
 */
class ObsahStranekPresenter extends BasePresenter {

    /**
     * @var Forms\PostForm
     */
    private $_PostForm;
    
    final function injectPostForm(Forms\PostForm $factory)
    {
        $this->_PostForm = $factory;
    }
    
    protected function createComponentPostForm()
    {
        return $this->_PostForm->createForm();
    }

    public function renderDefault() {
        
    }

    public function renderPridatClanek() {
        
    }

}