<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Models\Entity\Image;
use Models\Image\Image as ImageModel;
use Nette\Application\UI\Form;
use Nette\Forms\IControl;
use SplFileInfo;
/**
 * Description of FileUploadForm
 *
 * @author Tomáš Grasl
 */
class FileUploadForm extends BaseForm  {

    /** @var EntityManager */
    protected $_em;
    
    /** @var ImageModel */
    protected $_image;
    
    /** @var EntityRepository */
    protected $_categoryRepo;
    
    /** @var EntityRepository */
    protected $_imageRepo;

    /** @var Image */
    protected $_defaults;

    public function __construct(EntityManager $em, ImageModel $image) {
        $this->_em = $em;
        $this->_categoryRepo = $em->getRepository('Models\Entity\ImageCategory');
        $this->_imageRepo = $em->getRepository('Models\Entity\Image');
        $this->_image = $image;
    }
    
    public function createForm(Image $defaults = NULL)
    {
        if(!$defaults)
        {
            return $this->_addForm();
        }
        else
        {
            $this->_defaults = $defaults;
            
            return $this->_editForm(); 
        }
    }
    
    private function _addForm()
    {
        $form = new Form;

        $c = $this->prepareForFormItem($this->_categoryRepo->getImageCategories(), 'title'); 
        
        $form->addCheckbox('add_category', 'Přidat do galerie: ');
        
        $form->addSelect('image_category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');        
        
        $form->addUpload('fileselect', 'Nahrát nový soubor: ', TRUE)
             ->addRule(Form::MAX_FILE_SIZE, NULL, 1024 * 5000)
             ->addRule(Form::IMAGE, NULL)
             ->addRule(function (IControl $control){
                 return count($control->getValue()) <= 5;
             }, 'Maximalní počet souboru je 5');

        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');        
        
        $form->onSuccess[] = callback($this, 'addImage');
        
        $form['image_category']->addConditionOn($form['add_category'], $form::FILLED)
                               ->addRule($form::FILLED, 'Žádná kategorie nebyla vybrana.');        

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-download-alt"');
        $vybratBtn->add(' Nahrát soubor');
        
        return $form;        
    }
    
    private function _editForm()
    {
        $form = new Form;
        $defaults = $this->_defaults;
        
        $c = $this->prepareForFormItem($this->_categoryRepo->getImageCategories(), 'title'); 

        
        if($defaults->getCategory() == NULL){
            $check = false;
        }else{
            $check = true;
        }
        
        if($this->_defaults->getCategory())
        {
            $default['category'] = $this->_defaults->getCategory()->getId();  
        }
        else
        {
            $default['category'] = 0;
        }
        
        if(!$this->_defaults->getImageOrder() == NULL)
        {
            $default['order'] = $this->_defaults->getImageOrder();  
        }
        else
        {
            $default['order'] = 0;  
        }        
        $o = $this->prepareForFormItem($this->_imageRepo->getImageOrder($this->_defaults->getCategory()), 'imageOrder');
        
        $form->addText('image_title', 'Titulek: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($defaults->getTitle());

        $form->addTextArea('image_description', 'Popis: ')
             ->setHtmlId('editor')
             ->setDefaultValue($defaults->getDescription());
        
        $form->addCheckbox('add_category', 'Přidat do galerie: ')
             ->setDefaultValue($check);
        
        $form->addSelect('image_public', 'Publikovat: ', array(0 => 'nepublikovat', 1 => 'public'))
             ->addRule(Form::FILLED, null)
             ->setDefaultValue($defaults->getPublish());
        
        $form->addSelect('image_category', 'Kategorie: ', $c)
             ->setPrompt('- No category -')
             ->setDefaultValue($default['category']);
        
        $form->addSelect('image_order', 'Pořadí: ', $o)
             ->setDefaultValue($default['order']);

        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'editImage');
        
        $form['image_category']->addConditionOn($form['add_category'], $form::FILLED)
                               ->addRule($form::FILLED, 'Žádná kategorie nebyla vybrana.');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Upravit obrazek');
        
        return $form;        
    }
    
    public function addImage(Form $form)
    {
        $value = $form->values;
        $category = $this->_categoryRepo->getOne($value->image_category);
        $dir = $this->_image->getDir();
        try 
        {
            if(count($value->fileselect)){
                
                $x = (int) $this->_image->lastImageOrder($category);
                foreach($value->fileselect as $image)
                {
                    /* @var $image FileUpload */
                    $info = new SplFileInfo($dir.$image->getName());
                    $ext = '.'.pathinfo($info->getFilename(), PATHINFO_EXTENSION);
                    $fileName = $info->getBasename($ext);
                    $x = $x + 1;
                    $imageModel = new Image($image->getName());

                    if($value->add_category == TRUE){
                        $imageModel->setCategory($category);
                    }                    

                    $imageModel->setImageOrder($x);
                    $imageModel->setExt($ext);
                    $imageModel->setName($fileName);

                    $this->_em->persist($imageModel);                        

                    $image->move($dir.$image->getName());
                    $this->_image->createThumbs($image->getName());                    
                }
                $this->_em->flush();
            }
            $form->presenter->redirect('Image:default');
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
    }
    
    public function editImage(Form $form)
    {
        $value = $form->values;
        $category = $this->_categoryRepo->getOne($value->image_category);

        try 
        {
            $image = $this->_defaults;
            
            $image->setTitle($value->image_title);
            $image->setDescription($value->image_description);
            $image->setPublish($value->image_public); 

            if(!empty($category))
            {
                $this->_image->updateImageOrderAfterCategoryChange($image);
                $order = $this->_image->lastImageOrder($category) + 1;
                $image->setImageOrder($order);
                $image->setCategory($category);
            }
            else
            {
                $this->_image->updateImageOrderAfterCategoryChange($image);
                $order = $this->_image->lastImageOrder(NULL) + 1;
                $image->setImageOrder($order);                
                $image->removeCategory();
            }

            $this->_em->flush($image);
            
            $form->presenter->redirect('Image:default');
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }        
    }
}
