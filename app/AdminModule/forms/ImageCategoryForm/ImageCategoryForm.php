<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\ImageCategory\ImageCategory;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

/**
 * Description of CategoryForm
 *
 * @author Tomáš Grasl
 */
class ImageCategoryForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
     /** @var EntityRepository */
    protected $_imageCategory;
    
    /** @var ImageCategory */
    protected $_defaults;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_imageCategory = $em->getRepository('Models\Entity\ImageCategory\ImageCategory');
    }
    
    public function createForm(ImageCategory $defaults = NULL)
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
        
        $form->addText('category_title', 'Title: ')
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);
        
        $form->addText('category_slug', 'Slug: ')
             ->addRule(Form::MAX_LENGTH, null, 32);
        
        $form->addTextArea('category_text', 'Text: ')
             ->addRule(Form::MAX_LENGTH, null, 150);
        
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
    
    private function _editForm()
    {
        $form = new Form;
        
        $form->addText('category_title', 'Title: ')
             ->setDefaultValue($this->_defaults->getTitle())   
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);
        
        $form->addText('category_slug', 'Slug: ')
             ->setDefaultValue($this->_defaults->getSlug())   
             ->addRule(Form::MAX_LENGTH, null, 32);
        
        $form->addTextArea('category_text', 'Text: ')
             ->setDefaultValue($this->_defaults->getDescription())   
             ->addRule(Form::MAX_LENGTH, null, 150);
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Upravit categorii');
        
        return $form;               
    }

    public function onsuccess(Form $form)
    {
        try 
        {
            $value = $form->values;
            $value->category_slug ? $slug = $value->category_slug : $slug = $value->category_title;
            $slug = Strings::webalize($slug);        

            if($this->_defaults)
            {
                if(!($value->category_title == $this->_defaults->getTitle()))
                {
                    if($this->_imageCategory->findOneBy(array('title' => $value->category_title)))
                    {
                        throw new FormException('Category with name "' . $value->category_title . '" exist.');  
                    }
                }

                $category = $this->_defaults;
                $category->setTitle($value->category_title);
                $category->setSlug($slug);
                $category->setDescription($value->category_text);
                $this->_em->flush($category);
                
                $form->presenter->redirect('ImageCategory:editCategory', $this->_defaults->getId());
            }
            else
            {
                if($this->_imageCategory->findOneBy(array('title' => $value->category_title)))
                {
                    throw new FormException('Category with name "' . $value->category_title . '" exist.');  
                }

                $category = new ImageCategory($value->category_title, $slug, $value->category_text);

                $this->_em->persist($category);
                $this->_em->flush($category);
                
                $form->presenter->redirect('ImageCategory:default');
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
    }  
}
