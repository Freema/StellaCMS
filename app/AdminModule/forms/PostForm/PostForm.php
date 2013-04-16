<?php
namespace AdminModule\Forms;
/**
 * Description of DataSearch
 * 
 * @author Tomáš Grasl
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Post\Post;
use Nette\Application\UI\Form;

class PostForm extends BaseForm
{
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
        
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title');
        
        $form->addText('title', 'Title: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addSelect('category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');
        
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
        $user = $this->_em->getRepository('Models\Entity\User\User');
        
        $post = new Post($user->find($form->presenter->getUser()->getId()), $value->text, $value->title);
        $post->setCategory($this->_category->getOne($value->category));
        $this->_em->persist($post);
        
        try {
            $this->_em->flush();
        } catch(\PDOException $e) {
            dump($e);
            die();
        }        
        
        $form->presenter->redirect('ObsahStranek:addArticle');
    }    
    
}