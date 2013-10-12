<?php
namespace Components\Comments;

use AdminModule\Forms\FormException;
use Models\Comment\Comment;
use Models\Entity\Post\Post;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

/**
 * Description of Comments
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class Comments extends Control{
    
    /**
     * @var Comment 
     */
    private $_service;
    
    /**
     * @var Post
     */
    private $_post;
    
    public function __construct(Comment $service,  Post $post) {
        parent::__construct();        
        
        $this->_post = $post;
        $this->_service = $service;
    }

    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'Comments.latte');
        $query = $this->_service->setFirstResult($this->_post->getId());
        $template->tab = $query->loadCommetTab();
        dump($template->tab);
        $template->render();
    }
    
    protected function createComponentAddForm()
    {
        $form = new Form;
        
        $form->addText('comment_title', 'Titulek: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 100);
        
        $form->addText('comment_user', 'Vaše jmeno: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 50);
        
        $form->addText('comment_email', 'Email: ')
             ->addRule(Form::EMAIL, NULL);   
        
        $form->addTextArea('comment_message', 'Vaše zprava: ')
             ->addRule(Form::FILLED, NULL)
             ->addRule(Form::MAX_LENGTH, NULL, 350);
        
        $form->addSubmit('comment_submit', 'Přidat komentář')
             ->setAttribute('class', 'btn btn-info');
        
        $form->onSuccess[] = callback($this, 'addComment'); 
        
        return $form;
    }
    
    public function addComment(Form $form)
    {
        try 
        {
            $this->_service->insertNewComment($form, $this->_post);
            $this->presenter->redirect('this');
        }
        catch(FormException $e)
        {
            $form->addError($e->getMessage());
            if($form->presenter->isAjax())
            {
                $form->presenter->payload->status = 'error';                
                $form->presenter->payload->error = $form->errors;
                $form->presenter->terminate();
            }
        }
    }
}
