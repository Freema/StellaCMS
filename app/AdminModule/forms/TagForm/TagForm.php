<?php
namespace AdminModule\Forms;

use Kdyby\Doctrine\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Tag;
use Nette\Application\UI\Form;
/**
 * Description of LinkForm
 *
 * @author Tomáš Grasl
 */
class TagForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
     /** @var EntityRepository */
    protected $_tag;
    
    /** @var Tag */
    protected $_defaults;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_tag = $em->getDao('Models\Entity\Tag');
    }
    
    public function createForm(Tag $defaults = NULL)
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
        
        $form->addText('tag_name', 'Název: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 40);
        
        $form->addText('tag_slug', 'Název v URL: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 40);
        
        $form->addTextArea('tag_description', 'Popis: ')
             ->addRule(Form::MAX_LENGTH, NULL, 250);
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Vytvořit nový štítek');
        
        return $form;        
    }

    private function _editForm()
    {
        $form = new Form;
        
        $form->addText('tag_name', 'Název: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 40)
             ->setDefaultValue($this->_defaults->getName());
        
        $form->addText('tag_slug', 'Název v URL: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 40)
             ->setDefaultValue($this->_defaults->getSlug());
        
        $form->addTextArea('tag_description', 'Popis: ')
             ->addRule(Form::MAX_LENGTH, NULL, 250)
             ->setDefaultValue($this->_defaults->getDescription());
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Aktualizovat');
        
        return $form;       
    }
    
    public function onsuccess(Form $form)
    {
        try 
        {
            $value = $form->values;
            
            if($this->_defaults)
            {
                $link = \Nette\Utils\Strings::webalize($value->tag_slug);
                if(!($link == $this->_defaults->getSlug()))
                {
                    if($this->_tag->findOneBy(array('slug' => $link)))
                    {
                        throw new FormException('Tag url "' . $value->tag_slug . '" exist.');  
                    }
                }

                if(!($value->tag_name == $this->_defaults->getName()))
                {
                    if($this->_tag->findOneBy(array('name' => $value->tag_name)))
                    {
                        throw new FormException('Tag with name "' . $value->tag_name . '" exist.');  
                    }
                }
                
                $tag = $this->_defaults;
                $tag->setName($value->tag_name);
                $tag->setSlug($link);
                $tag->setDescription($value->tag_description);
                $this->_em->flush($tag);

                $form->presenter->redirect('Tag:editTag', $this->_defaults->getId());                  
            }
            else
            {
                $link = \Nette\Utils\Strings::webalize($value->tag_slug);

                if($this->_tag->findOneBy(array('slug' => $link)))
                {
                    throw new FormException('Tag url "' . $value->tag_slug . '" exist.');  
                }

                if($this->_tag->findOneBy(array('name' => $value->tag_name)))
                {
                    throw new FormException('Tag with name "' . $value->tag_name . '" exist.');  
                }
                
                $tag = new Tag($value->tag_name);
                $tag->setSlug($link);
                $tag->setDescription($value->tag_description);
                
                $this->_em->persist($tag);
                $this->_em->flush();

                $form->presenter->redirect('Tag:default');
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
    } 
}
