<?php
namespace AdminModule\Forms;
/**
 * Description of SlideShowForm
 * 
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Components\Slideshow\SlideshowService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Nette\Application\UI\Form;

class SlideShowForm extends BaseForm
{
    /** @var EntityManager */
    protected $_em;
    
    /** @var SlideShow */
    protected $_slideShowService;
    
    /** @var EntityRepository */
    protected $_category;

    /** @var Post */
    protected $_defaults;
    
    public function __construct(EntityManager $em, SlideshowService $service) {
        $this->_em = $em;
        $this->_slideShowService = $service;
        $this->_category = $em->getRepository('Models\Entity\Category\Category');
    }
    
    public function createForm($defaults = NULL)
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

        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title', TRUE);
        
        $form->addHidden('slide_show_file')
             ->addRule(Form::FILLED, NULL);
        
        $form->addSelect('slide_show_script', 'Typ: ', array('boostrap', 'flexbox'))
             ->addRule(Form::FILLED, NULL);
        
        $form->addSelect('slide_show_category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Vytvořit slideshow');
        
        return $form;        
    }
    
    private function _editForm()
    {
        $form = new Form;
        
        $t = $this->prepareForFormItem($this->_tag->getTags(), 'name');        
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title', TRUE);
        
        $default = array();
        if($this->_defaults->getCategory())
        {
            $default['category'] = $this->_defaults->getCategory()->getId();            
        }
        else
        {
            $default['category'] = 0;
        }
        
        if($this->_defaults->getTags())
        {
            $default['tags'] = array();
            foreach ($this->_defaults->getTags() as $value)
            {
                $default['tags'][] = $value->getId();
            }
        }
        
        $form->addText('title', 'Title: ')
             ->setDefaultValue($this->_defaults->getTitle())
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addSelect('category', 'Kategorie: ', $c)
             ->setDefaultValue($default['category'])   
             ->setPrompt('- No category -');
        
        $form->addTextArea('text', 'Text: ')
             ->setDefaultValue($this->_defaults->getContent())   
             ->setHtmlId('editor');

        $form->addCheckboxList('tags', 'Štítky: ', $t)
             ->setDefaultValue($default['tags'])
             ->setAttribute('class', 'checkbox')
             ->getSeparatorPrototype()->setName(NULL); 
        
        $form->addSelect('publish', 'Publikovat: ', array('Koncept', 'Publikovat'))
             ->addRule(Form::FILLED, NULL)
             ->setDefaultValue($this->_defaults->getPublish());
        
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
           
            if($this->_defaults)
            {
                dump($value);
            }
            else
            {
                dump($value);
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
        
    }    
    
}