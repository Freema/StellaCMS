<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Link\Link;
use Nette\Application\UI\Form;
/**
 * Description of LinkForm
 *
 * @author Tomáš
 */
class LinkForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
     /** @var EntityRepository */
    protected $_link;
    
    /** @var Link */
    protected $_defaults;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_link = $em->getRepository('Models\Entity\Link\Link');
    }
    
    public function createForm(Link $defaults = NULL)
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
        
        $radio = array(
          '_blank'  => ' Otevřít odkaz v novém okně nebo záložce prohlížeče.',
          '_top'  => ' Otevřít odkaz v aktuálním okně nebo záložce prohlížeče, a to v celém rozsahu i při použití rámů na webu.',
          '_none'  => ' Otevřít odkaz v aktuálním okně nebo záložce prohlížeče.',
        );
        
        $form->addText('link_title', 'Title: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addText('link_url', 'Adresa webu: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addText('link_description', 'Popis: ')
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addRadioList('link_target', 'Zobrazení odkazu: ', $radio)
             ->getSeparatorPrototype()->setName(NULL);
        
        $form->addText('link_css_class', 'CSS trida odkazu: ')
             ->addRule(Form::MAX_LENGTH, NULL, 50);
        
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

    private function _editForm()
    {
        $form = new Form;
        
        $radio = array(
          '_blank'  => ' Otevřít odkaz v novém okně nebo záložce prohlížeče.',
          '_top'  => ' Otevřít odkaz v aktuálním okně nebo záložce prohlížeče, a to v celém rozsahu i při použití rámů na webu.',
          '_none'  => ' Otevřít odkaz v aktuálním okně nebo záložce prohlížeče.',
        );
        

        $form->addText('link_title', 'Title: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100)
             ->setDefaultValue($this->_defaults->getTitle());
        
        $form->addText('link_url', 'Adresa webu: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100)
             ->setDefaultValue($this->_defaults->getUrl());
        
        $form->addText('link_description', 'Popis: ')
             ->addRule(Form::MAX_LENGTH, NULL, 100)
             ->setDefaultValue($this->_defaults->getDescription());
        
        $form->addRadioList('link_target', 'Zobrazení odkazu: ', $radio)
             ->setDefaultValue($this->_defaults->getTarget())
             ->getSeparatorPrototype()->setName(NULL);
        
        $form->addText('link_css_class', 'CSS trida odkazu: ')
             ->addRule(Form::MAX_LENGTH, NULL, 50)
             ->setDefaultValue($this->_defaults->getCssClass());
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');
        
        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Upravit odkaz');
        
        return $form;  
    }
    
    public function onsuccess(Form $form)
    {
        try 
        {
            $value = $form->values;
            
            if($this->_defaults)
            {
                if(!($value->link_title == $this->_defaults->getTitle()))
                {
                    if($this->_link->findOneBy(array('title' => $value->link_title)))
                    {
                        throw new FormException('Post with name "' . $value->link_title . '" exist.');  
                    }
                }
                
                $link = $this->_defaults;
                $link->setTitle($value->link_title);
                $link->setUrl($value->link_url);
                $link->setDescription($value->link_description);
                $link->setTarget($value->link_target);
                $link->setCssClass($value->link_css_class);
                
                $this->_em->flush($link);
                $form->presenter->redirect('Link:editLink', $this->_defaults->getId());                  
            }
            else
            {
                $link = new Link($value->link_title, $value->link_url, $value->link_description);
                $link->setTarget($value->link_target);
                $link->setCssClass($value->link_css_class);
                
                $this->_em->persist($link);
                $this->_em->flush();

                $form->presenter->redirect('Link:default');
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
    } 
}
