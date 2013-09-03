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
        $this->_category = $em->getRepository('Models\Entity\ImageCategory\ImageCategory');
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

        $c = $this->prepareForFormItem($this->_category->getImageCategories(), 'title');
        $types = $this->_slideShowService->type;
        
        foreach ($types as $key => $value)
        {
            $s[$key] = $value['name'];
        }
        
        $form->addText('slide_show_name', 'Název: ')
             ->addRule(Form::FILLED, NULL);
        
        $form->addSelect('slide_show_script', 'Typ: ', $s)
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
            $post = $form->getHttpData();
            $value = $form->getValues();
            if(!isset($post['slide_show_file']))
            {
                $form->getPresenter()->flashMessage('Není vybraný žádný obrázek', 'error');
                $form->getPresenter()->redirect('this');
            }
            
            if($this->_defaults)
            {
                dump($value);
            }
            else
            {
                $category = $this->_category->findOneBy(array('id' => $value->slide_show_category));
                
                $script = new \Models\Entity\SlideShow\SlideShowScript($value->slide_show_name,'');
                
                if(isset($this->_slideShowService->type[$value->slide_show_script]))
                {
                    $script->setOptions($this->_slideShowService->type[$value->slide_show_script]);
                }
                $this->_em->persist($script);
                $this->_em->flush($script);   
                
                foreach ($post['slide_show_file'] as $key => $value)
                {
                    $slide = new \Models\Entity\SlideShow\SlideShow($value['file']);
                    $slide->setImageOrder($key);
                    $slide->setScript($script);
                    
                    if(!($category == NULL))
                    {
                        $slide->setCategory($category);
                    }
                    $this->_em->persist($slide);     
                }
                $this->_em->flush($slide);
                
                $form->presenter->flashMessage('Slideshow byla vytvořena.', 'success');
                $form->presenter->redirect('SlideShow:default');
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
        
    }    
    
}