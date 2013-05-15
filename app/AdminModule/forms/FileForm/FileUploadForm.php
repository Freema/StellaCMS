<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Form;
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

    public function __construct(EntityManager $em) {
        $this->_em = $em;
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
        
        $form->addMultyFileUpload('fileselect', 'Nahrát nový soubor: ');

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
        try 
        {
            $value = $form->values;
            
            if(count($value->fileselect)){
                foreach($value->fileselect as $image){
                    
                    /* @var $image \Nette\Http\FileUpload */
                    $image->move($this->_dir.$image->getName());
                    $imageModel = new \Models\Entity\Image\Image($image->getName());
                    $this->_em->persist($imageModel);
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
