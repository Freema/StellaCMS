<?php
namespace AdminModule;

use AdminModule\Forms\FileUploadForm;
use Components\Paginator\PagePaginator;
use Models\Entity\Image as Image2;
use Models\Image\Image;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Form;
use Stella\ModelException;
/**
 * Description of ImagePresenter
 *
 * @author Tomáš Grasl
 */
class ImagePresenter extends BasePresenter {
    
    /**
     * @var FileUploadForm 
     */
    private $_fileUploadForm;
    
    /** @var Image */
    private $_Image;
    
    /** @var Image2 */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'name'          => 'NONE',
        'order'         => 'NONE',
        'categorii'     => 'NONE',
        'public'        => 'NONE',
        'uploadet_at'   => 'NONE',
        'id'            => 'NONE',
        );
    
    /** @persistent */
    public $filter = array('ext' => 'all');
    
    /** @persistent */
    public $editor = NULL;

    final function injectFileUploadForm(FileUploadForm $form) {
        $this->_fileUploadForm = $form;
    }
    
    final function injectImage(Image $service)
    {
        $this->_Image = $service;
    }
    
    public function actionDefault($page, array $sort, array $filter)
    {
       // $this->_Image->setFilter($filter);
        $this->_Image->setSort($sort);
        
        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if(is_null($page))
        {
            $page = 1;
        }
       
        $paginator->page = $page;
        $paginator->itemCount = $this->_Image->imageItemsCount();
 
        $this->_Image->setFirstResult($paginator->getOffSet());        
        $this->_Image->setMaxResults($paginator->getMaxResults());
      
        $this->template->tab = $this->_Image->loadImageTab();        
    }

    public function renderDefault()
    {
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
        
        $info = $this->_Image->findImages($this->_Page->getName().'*');
        
        $this->template->data = $this->_Page; 
        $this->template->fileInfo = $info;
    }
    
    public function actionImageView($name)
    {
        if($this->isAjax())
        {
            $this->template->imageUrl = $name;
            $this->setView('imageView-ajax');
        }
        else
        {
            $this->setView('imageView');
            $this->template->imageUrl = $name;
        }
    }
    
    public function actionGalerie($resize = NULL)
    {
        $size = $this->_Image->getSize();        
        
        $this->template->registerHelper('largeThumb', function($name) use ($size) {
           $helper = Image::largeThumb($name, $size); 
           return $helper;
        });
        
        $this->template->registerHelper('smallThumb', function($name) use ($size) {
           $helper = Image::smallThumb($name, $size); 
           return $helper;
        });        
        
        $this->template->resize = $resize;
        $imageRes =  $this->_Image;
        $imageRes->setSort(array('order' => 'ASC'));
        $this->template->images = $imageRes->loadImageTab();
    }
    
    /**
     * @param string $editor ID editoru 
     * @param string $resize Velikost
     */
    public function actionSelectImage($editor, $resize = NULL) {
        
        if($this->isAjax())
        {
            $this->setView('selectImage-ajax');
        }
        else
        {
            $this->setLayout('iframeLayout');
        }
                
        $size = $this->_Image->getSize();        

        $this->template->registerHelper('largeThumb', function($name) use ($size) {
           $helper = Image::largeThumb($name, $size); 
           return $helper;
        });
        
        $this->template->registerHelper('smallThumb', function($name) use ($size) {
           $helper = Image::smallThumb($name, $size); 
           return $helper;
        });           

        $imageRes =  $this->_Image;
        $imageRes->setSort(array('order' => 'ASC'));
        
        /**
         * template return
         */
        $this->template->resize = $resize;        
        $this->template->editor = $editor;
        $this->template->images = $imageRes->loadImageTab();    
    }
    
    public function actionAutocomplete()
    {
        $data = $this->_Image->getImageRepository()->findAllImageName();
        $json = array();
        foreach ($data as $name)
        {
            $json[] = $name['name'];
        }
        $this->sendResponse(new JsonResponse($json));
    }

    /**
     * Controller pro vymazani obrazku.
     * @param integer $id
     */
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
    
    public function handleImageSort()
    {
        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else 
        {
            $data = $this->getHttpRequest()->post['listOrder'];
            $this->_Image->updateImageOrderList($data);
        }        
    }
    
    /**
     * @param integer $public
     * @param integer $imageId
     */
    public function handlePublicImage($public, $imageId)
    {
        $this->_Categories->setCategorie('galerie');
        $this->_Categories->changePublic($public, $imageId);
        
        $public == 1 ? $set = 'zveřejněna' : $set = 'zneverejněna';
        $this->flashMessage('Fotka byla ' . $set . '!');

        if(!$this->isAjax()){
            $this->redirect('this');
        }
        else 
        {
            $this->invalidateControl('imageBox');
            $this->invalidateControl('flashMessages');
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
    
    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }
    
    protected function createComponentSearchForm()
    {
        $form = new Form();
        
        $form->addSelect("search_box", "hledat: ")
             ->setParent("class", "search-query");
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'findImageName');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Vytvořit categorii');
        
        return $form;
    }
}