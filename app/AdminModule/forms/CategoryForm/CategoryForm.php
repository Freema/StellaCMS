<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Category\Category;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

/**
 * Description of CategoryForm
 *
 * @author Tomáš Grasl
 */
class CategoryForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
     /** @var EntityRepository */
    protected $_category;
    
    /** @var Category */
    protected $_defaults;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
        $this->_category = $em->getRepository('Models\Entity\Category\Category');
    }
    
    public function createForm(Category $defaults = NULL)
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
        
        $form->addText('title', 'Title: ')
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);
        
        $form->addText('tag_slug', 'Slug: ')
             ->addRule(Form::MAX_LENGTH, null, 32);
        
        $form->addSelect('parent', 'Parent: ', $c)
             ->setPrompt('- No parent -');
        
        $form->addTextArea('text', 'Text: ')
             ->setHtmlId('editor');
        
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
       
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title');
        
        if($this->_defaults->getParent() == NULL)
        {
            $default = 0;
        }
        else
        {
            $default = $this->_defaults->getParent()->getId();
        }
        
        $form->addText('title', 'Title: ')
             ->setDefaultValue($this->_defaults->getTitle())   
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);
        
        $form->addText('tag_slug', 'Slug: ')
             ->setDefaultValue($this->_defaults->getSlug())   
             ->addRule(Form::MAX_LENGTH, null, 32);
        
        $form->addSelect('parent', 'Parent: ', $c)
             ->setDefaultValue($default)
             ->setPrompt('- No parent -');
        
        $form->addTextArea('text', 'Text: ')
             ->setDefaultValue($this->_defaults->getDescription())   
             ->setHtmlId('editor');
        
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

            $value->tag_slug ? $slug = $value->tag_slug : $slug = $value->title;

            $slug = Strings::webalize($slug);        
            $parent = $this->_category->getOne($value->parent);        

            if($this->_defaults)
            {
                if(!($value->title == $this->_defaults->getTitle()))
                {
                    if($this->_category->findOneBy(array('title' => $value->title)))
                    {
                        throw new FormException('Category with name "' . $value->title . '" exist.');  
                    }
                }

                $category = $this->_defaults;
                $category->setTitle($value->title);
                $category->setSlug($slug);
                $category->setDescription($value->text);

                if(!empty($parent)){
                    $category->setParent($parent);
                }
                else
                {
                    $category->removeParent();
                }
                
                $this->_em->flush($category);
                
                $form->presenter->redirect('Category:editCategory', $this->_defaults->getId());
            }
            else
            {
                if($this->_category->findOneBy(array('title' => $value->title)))
                {
                    throw new FormException('Category with name "' . $value->title . '" exist.');  
                }

                $category = new Category($value->title, $slug, $value->text);

                if(!empty($parent)){
                    $category->setParent($parent);
                }  

                $this->_em->persist($category);
                $this->_em->flush($category);
                
                $form->presenter->redirect('Category:editCategory', $this->_defaults->getId());
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
    }  
}
