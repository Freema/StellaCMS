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
    
    /** @var Image */
    private $_Image;
    
    /** @var Models\Entity\Image\Image */
    private $_Page;
    
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
    
    public function actionEditImage($id)
    {
        if(!($this->_Page = $this->_Image->getImageRepository()->getOne($id)))
        {
            $this->flashMessage('Image does not exist.', 'error');
            $this->redirect('default');
        }
        $this->template->data = $this->_Page;        
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
            $this->flashMessage($e->getMessage());
            $this->redirect('this');
        }
    }
    
    protected function createComponentFileUpload()
    {
        return $this->_fileUploadForm->createForm();
    }
    
    protected function createComponentFileEditForm()
    {
        return $this->_fileUploadForm->createForm($this->_Page);
    }

}