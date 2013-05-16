<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Models\Entity\Image\Image;
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
    
    /**
     * @var string
     */
    protected $_dir;
    
    /** @var EntityRepository */
    protected $_category;
    
    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_category = $em->getRepository('Models\Entity\Category\Category');        
    }
    
    /**
     * @param string $dir
     */
    public function setDir($dir)
    {
        $this->_dir = $dir;
    }
    
    
    public function createForm()
    {
        return $this->_addForm();
    }
    
    private function _addForm()
    {
        $form = new Form;

        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title'); 
        
        $form->addSelect('category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');        
        
        $form->addMultyFileUpload('fileselect', 'Nahrát nový soubor: ')
             ->addRule(Form::MAX_FILE_SIZE, NULL, 1024 * 5000)
             ->addRule(Form::IMAGE, NULL)
             ->addRule(function (IControl $control){
                 return count($control->getValue()) <= 5;
             }, 'Maximalní počet souboru je 5');

        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');        
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-download-alt"');
        $vybratBtn->add(' Nahrát soubor');
        
        return $form;        
    }
    
    public function onsuccess(Form $form)
    {
        
        $value = $form->values;
        $category = $this->_category->getOne($value->category);
        try 
        {
            if(count($value->fileselect)){
                foreach($value->fileselect as $image){
                    
                    /* @var $image FileUpload */
                    
                    $info = new SplFileInfo($this->_dir.$image->getName());
                    $ext = '.'.pathinfo($info->getFilename(), PATHINFO_EXTENSION);
                    $fileName = $info->getBasename($ext);
                    
                    $imageModel = new Image($image->getName());
                    
                    if(!empty($category)){
                        $imageModel->setCategory($category);
                    }                    
                    
                    $imageModel->setExt($ext);
                    $imageModel->setName($fileName);
                    
                    $this->_em->persist($imageModel);
                    $image->move($this->_dir.$image->getName());
                    
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

}
