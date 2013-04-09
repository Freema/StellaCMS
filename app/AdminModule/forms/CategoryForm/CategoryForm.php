<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Category\Category;
use Nette\Application\UI\Form;
use Nette\Object;

/**
 * Description of CategoryForm
 *
 * @author Tomáš
 */
class CategoryForm extends Object {
    
    /** @var EntityManager */
    protected $_em;
    
     /** @var EntityRepository */
    protected $_category;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_category = $em->getRepository('Models\Entity\Category\Category');
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
        
        $form->addSelect('parent', 'Parent: ')
             ->setPrompt('- No parent -');
        
        $form->addTextArea('text', 'Text: ')
             ->setHtmlId('editor');
        
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
        
        $category = new Category($value->title, $value->text);

        $parent = $this->_category->getOne($value->parent);
        if(isset($parent)){
            $category->setParent($parent);
        }

        $this->_em->persist($category);        
        try {
            $this->_em->flush();
        } catch(\PDOException $e) {
            dump($e);
            die();
        }
        
        $form->presenter->redirect('Category:addCategory');        
    }  
}
