<?php
namespace AdminModule;

use AdminModule\Forms\SlideShowForm;
use Components\Paginator\PagePaginator;
use Components\Slideshow\SlideshowService;
use Models\Image\Image;

/**
 * Description of SlideShowPresenter
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class SlideShowPresenter extends BasePresenter {

    /** @var Image */
    private $_Image;    
    
    /**
    * @var Forms\SlideShowForm
    */
    private $_SlideShowForm;
    
    /**
     * @var SlideshowService 
     */
    private $_SlideShow;
    
    /**
    * @var Slideshow
    */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'id'            => 'NONE',
        'script'        => 'NONE',
        'file'          => 'NONE',
        'order'         => 'NONE',
        'categorii'     => 'NONE',
        'uploadet_at'   => 'NONE',
        );    
    
    final function injectSlideShowForm(SlideShowForm $form)
    {
        $this->_SlideShowForm = $form;
    }
    
    final function injectSlideShow(SlideshowService $service)
    {
        $this->_SlideShow = $service;
    }    
    
    final function injectImage(Image $service)
    {
        $this->_Image = $service;
    }    

    protected function createComponentSlideShowForm()
    {
        return $this->_SlideShowForm->createForm();
    }
    
    public function actionDefault($page, array $sort)
    {
        $size = $this->_Image->getSize();        

        $this->template->registerHelper('smallThumb', function($name) use ($size) {
           $helper = Image::smallThumb($name, $size); 
           return $helper;
        });          
        
        $this->_SlideShow->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_SlideShow->SlideShowItemsCount();
 
        $this->_SlideShow->setFirstResult($paginator->getOffSet());        
        $this->_SlideShow->setMaxResults($paginator->getMaxResults());
        
        $this->template->tab = $this->_SlideShow->loadSlideShowTab();
    }    
    
    public function renderAddSlideShow() {
        
        if($this->isAjax())
        {
            $parrams = $this->request->getPost();
            if(isset($parrams['status']))
            {
                if(array_search("", $parrams['status']) === 0)
                {
                    $this->flashMessage('Image not selected', 'error');
                    $this->invalidateControl('flashMessages');
                }
                else
                {
                    $size = $this->_Image->getSize();        

                    $this->template->registerHelper('smallThumb', function($name) use ($size) {
                       $helper = Image::smallThumb($name, $size); 
                       return $helper;
                    });  

                    $this->template->parrams = $parrams['status']; 
                }

            }
            
            $this->invalidateControl('slideSowPreviw');            
        }
    }
    
    public function actionUpdateSlideShow($id)
    {
        if(!($this->_Page = $this->_SlideShow->getSlideShowScriptRepository()->getOne($id)))
        {
            $this->flashMessage('SlideShow does not exist.', 'error');
            $this->redirect('default');
        }
        
        $size = $this->_Image->getSize();        

        $this->template->registerHelper('smallThumb', function($name) use ($size) {
           $helper = Image::smallThumb($name, $size); 
           return $helper;
        });         
        
        $this->template->data = $this->_Page;        
    }
    
    public function actionImage($name)
    {
        
    }
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }        
}