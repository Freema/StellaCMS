<?php
namespace AdminModule\Forms;
/**
 * Description of PostForm
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

    /** @var EntityRepository */
    protected $_tag;

    /** @var Post */
    protected $_defaults;
    
    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_category = $em->getRepository('Models\Entity\Category\Category');
        $this->_tag = $em->getRepository('Models\Entity\Tag\Tag');
    }
    
    public function createForm(Post $defaults = NULL)
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
        
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title');
        $t = $this->prepareForFormItem($this->_tag->getTags(), 'name');
        
        $form->addText('title', 'Title: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addSelect('category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');
        
        $form->addTextArea('text', 'Text: ')
             ->setHtmlId('editor')->getControlPrototype();
        
        $form->addCheckboxList('tags', 'Štítky: ', $t)
             ->setAttribute('class', 'checkbox')
             ->getSeparatorPrototype()->setName(NULL);
        
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
    
    private function _editForm()
    {
        $form = new Form;
        
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title');
        
        if($this->_defaults->getCategory() == NULL)
        {
            $default = 0;
        }
        else
        {
            $default = $this->_defaults->getCategory()->getId();
        }
        
        $form->addText('title', 'Title: ')
             ->setDefaultValue($this->_defaults->getTitle())
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addSelect('category', 'Kategorie: ', $c)
             ->setDefaultValue($default)   
             ->setPrompt('- No category -');
        
        $form->addTextArea('text', 'Text: ')
             ->setDefaultValue($this->_defaults->getContent())   
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
        try 
        {
            $value = $form->values;
            $user = $this->_em->getRepository('Models\Entity\User\User');
            $category = $this->_category->getOne($value->category);
            
            if($this->_defaults)
            {
                if(!($value->title == $this->_defaults->getTitle()))
                {
                    if($this->_em->getRepository('Models\Entity\Post\Post')->findOneBy(array('title' => $value->title)))
                    {
                        throw new FormException('Post with name "' . $value->title . '" exist.');  
                    }
                }
                
                $post = $this->_defaults;
                $post->setUser($user->find($form->presenter->getUser()->getId()));
                $post->setContent($value->text);
                $post->setTitle($value->title);
                
                if(!empty($category)){
                    $post->setCategory($category);
                }
                else
                {
                    $post->removeCategory();
                }

                $this->_em->flush($post);
                $form->presenter->redirect('ObsahStranek:editArticle', $this->_defaults->getId());                
            }
            else
            {
                
                dump($value);
                
                /**
                $post = new Post($user->find($form->presenter->getUser()->getId()), $value->text, $value->title);
                
                if(!empty($category)){
                    $post->setCategory($category);
                }                
                
                $this->_em->persist($post);
                $this->_em->flush();

                $form->presenter->redirect('ObsahStranek:addArticle');
                 * 
                 */
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
        
    }    
    
}