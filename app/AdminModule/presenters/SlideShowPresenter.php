<?php
namespace AdminModule;

use AdminModule\Forms\SlideShowForm;
use Components\Paginator\PagePaginator;
use Components\Slideshow\SlideshowService;
use Models\Entity\SlideShow\SlideShow;
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
    * @var SlideShow 
    */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'id'            => 'NONE',
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
    
    public function actionDefault($page, array $sort, $category)
    {
        /*
        $cFilter = $this->_PostForm->prepareForFormItem(
                $this->_Category->getCategoryRepository()->getCategories(),
                'title', 
                TRUE);
         * 
         */
        /*
        if(!is_null($category))
        {
            $cf_test = $this->_checkCategoryExist($category, $cFilter);
            dump($cf_test);
            $this->_Post->setFilter($cf_test);
        }
         * 
         */
        
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

       // $this->template->cFilter = $cFilter;
    }    
    
    public function actionAddSlideShow() {
        
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
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }        
}