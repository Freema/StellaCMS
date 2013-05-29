<?php
namespace AdminModule;

use Models\Image\Image;
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
    
    /**
     * @var Image 
     */
    private $_Image;
    
    final function injectFileUploadForm(Forms\FileUploadForm $form) {
        $this->_fileUploadForm = $form;
    }
    
    final function injectImage(Image $service)
    {
        $this->_Image = $service;
    }

    public function renderDefault() 
    {
        dump($this->_Image->loadImageTab());
        dump($this->_Image->getSize());
        //$this->template->tab = $this->_Image->findImages('*');
        
    }

    public function renderNewImage() {
        
    }
    
    protected function createComponentFileUpload()
    {
        return $this->_fileUploadForm->createForm();
    }

}