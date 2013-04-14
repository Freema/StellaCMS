<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Nette\Application\UI\Form;

/**
 * Description of CategoryForm
 *
 * @author Tomáš Grasl
 */
class MenuForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
     /** @var EntityRepository */
    protected $_menu;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_menu = $em->getRepository('Models\Entity\Menu\Menu');
    }
    
    public function createForm($id = NULL)
    {
        return $this->_addForm();
    }
    
    private function _addForm()
    {
        $form = new Form;
        
        $form->addText('title', 'Title: ')
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);
        
        $form->addText('url', 'Url: ')
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);

        $form->addText('description', 'Description: ')
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 255);
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Vytvořit categorii');
        
        return $form;        
    }

    public function onsuccess(Form $form)
    {
        
        $value = $form->values;
        
        $form->presenter->redirect('Menu:default');        
    }  
}
