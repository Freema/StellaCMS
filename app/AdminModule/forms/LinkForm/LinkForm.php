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
             ->addRule(Form::MAX_LENGTH, NULL, 30);
        
        $form->addRadioList('link_target', 'Zobrazení odkazu: ', $radio)
             ->getSeparatorPrototype()->setName(NULL);
        
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
            
            if(isset($this->_defaults))
            {
               
            }
            else
            {
                $post = new Link($value->link_title, $value->link_url, $value->link_description);
                $post->setTarget($value->link_target);
                
                $this->_em->persist($post);
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
