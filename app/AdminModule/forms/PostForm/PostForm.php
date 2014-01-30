<?php
namespace AdminModule\Forms;
/**
 * Description of PostForm
 * 
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Models\Entity\Post\Post;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

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
        
        $t = $this->prepareForFormItem($this->_tag->getTags(), 'name');
        $c = $this->prepareForFormItem($this->_category->getCategories(), 'title', TRUE);
        
        $form->addText('title', 'Title: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addSelect('category', 'Kategorie: ', $c)
             ->setPrompt('- No category -');
        
        $form->addText('slug', 'URL: ')
             ->setDefaultValue($this->_defaults->getSlug())   
             ->addRule(Form::MAX_LENGTH, null, 32);        
        
        $form->addTextArea('text', 'Text: ')
             ->setHtmlId('editor_add_post');
        
        $form->addCheckboxList('tags', 'Štítky: ', $t)
             ->setAttribute('class', 'checkbox')
             ->getSeparatorPrototype()->setName(NULL);
        
        $form->addSelect('publish', 'Publikovat: ', array('Koncept', 'Publikovat'))
             ->addRule(Form::FILLED, NULL)
             ->setDefaultValue(1);
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Přidat članek');
        
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
        
        $form->addText('slug', 'URL: ')
             ->setDefaultValue($this->_defaults->getSlug())   
             ->addRule(Form::MAX_LENGTH, null, 32);        
        
        $form->addTextArea('text', 'Text: ')
             ->setDefaultValue($this->_defaults->getContent())   
             ->setHtmlId('editor_edit_post');

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
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
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
            
            $value->slug ? $slug = $value->slug : $slug = $value->title;

            /* @var $slug string */
            $slug = Strings::webalize($slug);    
            
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
                $post->setSlug($slug);
                $post->setPublish($value->publish); 
                $post->setCreatedAt(new \DateTime('NOW'));

                $default = array();
                foreach ($this->_defaults->getTags() as $tagl)
                {
                    $default[] = $tagl->getId();
                }                
                
                /** $row najde rozdil mezi tagy */                
                $row = self::FormItemsDif($default, $value->tags);                

                if($row['remove'])
                {
                    foreach($row['remove'] as $remove)
                    {
                        $post->removeTag($this->_em->getRepository('Models\Entity\Tag\Tag')->findOneById($remove));
                    }
                }

                if($row['added'])
                {
                    foreach($row['added'] as $added)
                    {
                        $post->addTag($this->_em->getRepository('Models\Entity\Tag\Tag')->findOneById($added));
                    }
                }
                
                if(!empty($category)){
                    $post->setCategory($category);
                }
                else
                {
                    $post->removeCategory();
                }

                $this->_em->flush($post);
                $form->presenter->redirect('Post:editArticle', $this->_defaults->getId());
            }
            else
            {
                if($this->_em->getRepository('Models\Entity\Post\Post')->findOneBy(array('title' => $value->title)))
                {
                    throw new FormException('Post with name "' . $value->title . '" exist.');  
                }                
                
                $post = new Post($user->find($form->presenter->getUser()->getId()), $value->text, $value->title);
                $post->setPublish($value->publish);
                $post->setSlug($slug);

                if(!empty($category)){
                    $post->setCategory($category);
                }

                if($value->tags)
                {
                    foreach ($value->tags as $tag)
                    {
                        $post->addTag($this->_em->getRepository('Models\Entity\Tag\Tag')->findOneById($tag));
                    }
                }

                $this->_em->persist($post);
                $this->_em->flush();

                $form->presenter->redirect('Post:default');
            }
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
        }
        
    }    
    
}