<?php
namespace AdminModule\Forms;
/**
 * Description of DataSearch
 * @author Tomáš
 * 
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Post\Post;
use Nette\Application\UI\Form;
use Nette\Object;

class PostForm extends Object
{
    /** @var EntityManager */
    protected $_em;
    
    /** @var EntityRepository */
    protected $_user;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_user = $em->getRepository('Models\Entity\User\User');
    }
    
    public function createForm($id = NULL)
    {
        return $this->_addForm();
    }
    
    public function setDefauts(\DibiRow $defauts)
    {
        $this->_Defauts = $defauts;
    }
    
    private function _addForm()
    {
        $form = new Form;
        
        $form->addTextArea('text', 'Text: ')
             ->setHtmlId('editor');
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Upravit članek');
        
        return $form;        
    }

    public function onsuccess(Form $form)
    {
        $value = $form->values;
        
        $post = new Post($this->_user->find($form->presenter->getUser()->getId()), $value->text);
        $this->_em->persist($post);
        
        try {
            $this->_em->flush();
        } catch(\PDOException $e) {
            dump($e);
            die();
        }        
        
        $form->presenter->redirect('ObsahStranek:pridatClanek');
    }    
    
}