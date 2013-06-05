<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Models\Entity\Image\Image;
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
    protected $_category;
    
    /** @var Image */
    protected $_defaults;

    public function __construct(EntityManager $em, ImageModel $image) {
        $this->_em = $em;
        $this->_category = $em->getRepository('Models\Entity\ImageCategory\ImageCategory');
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

        $c = $this->prepareForFormItem($this->_category->getImageCategories(), 'title'); 
        
        $form->addCheckbox('add_category', 'Přidat do galerie: ');
        
        $form->addSelect('image_category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');        
        
        $form->addMultyFileUpload('fileselect', 'Nahrát nový soubor: ')
             ->addRule(Form::MAX_FILE_SIZE, NULL, 1024 * 5000)
             ->addRule(Form::IMAGE, NULL)
             ->addRule(function (IControl $control){
                 return count($control->getValue()) <= 5;
             }, 'Maximalní počet souboru je 5');

        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');        
        
        $form->onSuccess[] = callback($this, 'addImage');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-download-alt"');
        $vybratBtn->add(' Nahrát soubor');
        
        return $form;        
    }
    
    private function _editForm()
    {
        $form = new Form;
        $defaults = $this->_defaults;
        
        $c = $this->prepareForFormItem($this->_category->getImageCategories(), 'title'); 
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

        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');        
        
        $form->onSuccess[] = callback($this, 'editImage');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Upravit obrazek');
        
        return $form;        
    }
    
    public function addImage(Form $form)
    {
        $value = $form->values;
        $category = $this->_category->getOne($value->category);
        $dir = $this->_image->getDir();
        try 
        {
            if(count($value->fileselect)){
                foreach($value->fileselect as $image)
                {
                    /* @var $image FileUpload */

                    $info = new SplFileInfo($dir.$image->getName());
                    $ext = '.'.pathinfo($info->getFilename(), PATHINFO_EXTENSION);
                    $fileName = $info->getBasename($ext);

                    $imageModel = new Image($image->getName());

                    if($value->add_category == TRUE){
                        $imageModel->setCategory($category);
                    }                    

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
        $category = $this->_category->getOne($value->image_category);
        try 
        {
            $image = $this->_defaults;
            $image->setTitle($value->image_title);
            $image->setDescription($value->image_description);
            $image->setPublish($value->image_public); 

            if(!empty($category)){
                $image->setCategory($category);
            }
            else
            {
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
