<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Form;
use Models\Entity\Comment\Comment;

/**
 * Description of CategoryForm
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class CommentForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
    /** @var Comment */
    protected $_defaults;

    public function __construct(EntityManager $em) {
        $this->_em = $em;
    }

    public function replyForm($id)
    {
        $form = new Form;
        
        $form->addHidden('comment_reply_approve_id')
             ->addRule(Form::FILLED, NULL)
             ->setDefaultValue($id);
        
        $form->addTextArea('comment_reply_text', 'Text: ')
             ->setHtmlId('comment_reply_text' . $id);
        
        $form->addSubmit('comment_reply_submit', 'Odpovědět')
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'replySuccess');
        
        return $form;        
    }

    public function replySuccess(Form $form)
    {
        try 
        {
            $value = $form->values;
            
            dump($value);

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
