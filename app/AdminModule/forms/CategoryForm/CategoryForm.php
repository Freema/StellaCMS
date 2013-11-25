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
 * @author Tomáš Grasl <grasl.t@centrum.cz>
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
        
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title', TRUE);        
        
        $form->addText('title', 'Title: ')
             ->addRule(Form::FILLED, null)
             ->addRule(Form::MAX_LENGTH, null, 100);
        
        $form->addText('tag_slug', 'Slug: ')
             ->addRule(Form::MAX_LENGTH, null, 32);
        
        $form->addSelect('parent', 'Parent: ', $c)
             ->setPrompt('- No parent -');
        
        $form->addTextArea('text', 'Text: ')
             ->setHtmlId('editor_add_category');
        
        $form->addSelect('category_publish', 'Publikovat: ', array('Koncept', 'Publikovat'))
             ->addRule(Form::FILLED, NULL)
             ->setDefaultValue(1);      
        
        $form->addText('category_css_class', 'CSS trida odkazu: ')
             ->addRule(Form::MAX_LENGTH, NULL, 50);             
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Vytvořit categorii');
        
        return $form;        
    }
    
    private function _editForm()
    {
        $form = new Form;
       
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title', TRUE);
        
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
             ->setHtmlId('editor_edit_category');
        
        $form->addSelect('category_publish', 'Publikovat: ', array('Koncept', 'Publikovat'))
             ->addRule(Form::FILLED, NULL)
             ->setDefaultValue($this->_defaults->getPublish());        
        
        $form->addText('category_css_class', 'CSS trida odkazu: ')
             ->addRule(Form::MAX_LENGTH, NULL, 50)
             ->setDefaultValue($this->_defaults->getCssClass());        
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Upravit categorii');
        
        return $form;               
    }

    public function onsuccess(Form $form)
    {
        try 
        {
            $value = $form->values;

            $value->tag_slug ? $slug = $value->tag_slug : $slug = $value->title;

            /* @var $slug string */
            $slug = Strings::webalize($slug);        
            $parent = $this->_category->getOne($value->parent);        

            if($this->_defaults)
            {
                if(!($value->title == $this->_defaults->getTitle()) && 
                     $this->_category->findOneBy(array('title' => $value->title)))
                {
                    throw new FormException('Kategorie s jmenem: "' . $value->title . '" už existuje.');  
                }

                $category = $this->_defaults;
                $category->setTitle($value->title);
                $category->setSlug($slug);
                $category->setDescription($value->text);
                $category->setPublish($value->category_publish);
                $category->setCssClass($value->category_css_class);

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
                    throw new FormException('Kategorie s jmenem: "' . $value->title . '" už existuje.');  
                }

                $category = new Category($value->title, $slug, $value->text);

                if(!empty($parent)){
                    $category->setParent($parent);
                }  
                
                $category->setPublish($value->category_publish);
                $category->setCssClass($value->category_css_class);                

                $this->_em->persist($category);
                $this->_em->flush($category);
                
                if(!$form->presenter->isAjax())
                {
                    $form->presenter->redirect('Category:default');
                }
                else
                {
                    $form->presenter->payload->status = 'success';
                    $form->presenter->terminate();
                }
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
            if($form->getPresenter()->isAjax())
            {
                $form->getPresenter()->formMessageErrorResponse($form);
            }
        }
    }  
}
