<?php
namespace AdminModule;

use Models\Image\Image;
use Stella\ModelException;
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
        $this->template->tab = $this->_Image->loadImageTab();
        $size = $this->_Image->getSize();
        
        $this->template->registerHelper('smallThumb', function($name) use ($size) {
           $helper = Image::smallThumb($name, $size); 
           
           return $helper;
        });
        
        $this->template->registerHelper('largeThumb', function($name) use ($size) {
           $helper = Image::largeThumb($name, $size); 
           
           return $helper;
        });
    }

    public function renderNewImage() {
        
    }
    
    public function actionEditImage($id)
    {
        
    }
    
    public function handleDeleteMedia($id)
    {
        try
        {
            $this->_Image->deleteImage($id);
            if(!$this->isAjax()){
                $this->redirect('this');
            }
            else{
                $this->invalidateControl('articleTable');
                $this->invalidateControl('flashMessages');
            }              
        }
        catch(ModelException $e)
        {
            $this->flashMessage($e, 'error');
            $this->redirect('this');
        }
    }
    
    protected function createComponentFileUpload()
    {
        return $this->_fileUploadForm->createForm();
    }

}