<?php
namespace AdminModule;
/**
 * Description of ImagePresenter
 *
 * @author Tomáš Grasl
 */
class ImagePresenter extends BasePresenter {
    
    /**
     * @var Forms\FileUploadForm 
     */
    private $_fileUploadForm;
    
    final function injectFileUploadForm(Forms\FileUploadForm $form) {
        $this->_fileUploadForm = $form;
    }

    public function renderDefault() {
        
    }

    public function renderNewImage() {
        
    }
    
    protected function createComponentFileUpload()
    {
        return $this->_fileUploadForm->createForm();
    }

}