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
   

    public function __construct(EntityManager $em) {
        $this->_em = $em;
    }
    
    public function createForm()
    {
        return $this->_addForm();
    }
    
    private function _addForm()
    {
        $form = new Form;
        
        $form->addUpload('fileselect', 'Nahrát nový soubor: ');

        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');        
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Vytvořit odkaz');
        
        return $form;        
    }
    
    public function onsuccess(Form $form)
    {
        try 
        {
            $value = $form->values;
            dump($value);
            
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
    }     

}
