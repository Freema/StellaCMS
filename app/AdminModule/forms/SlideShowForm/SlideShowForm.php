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
                $uniq = $this->_slideShowService->getSlideShowScriptRepository()
                             ->findOneBy(array('name' => $value->slide_show_name));
                
                if($uniq)
                {
                    throw new FormException('Už existuje slideshow s timhle nazvem.');
                }
                
                $this->_slideShowService->insertNewSlideShow($value, $post);
                
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