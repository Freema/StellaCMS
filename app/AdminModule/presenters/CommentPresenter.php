<?php
namespace AdminModule;

/**
 * Description of CommentPresenter
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class CommentPresenter extends BasePresenter {
    
    /**
     * @var \Models\Comment\Comment  
     */
    private $_Comment;
    
    /**
     * @var Forms\CommentForm 
     */
    public $CommentFrom;
    
    /**
    * @var \Models\Entity\Comment\Comment 
    */
    private $_Page;
    
    /** @persistent */
    public $page;
    
    /** @persistent */
    public $sort = array(
        'author'        => 'NONE',
        'reaktions'     => 'NONE',
    );    

    final function injectCommentService(\Models\Comment\Comment $service)
    {
        $this->_Comment = $service;
    }
    
    final function injectCommentForm(Forms\CommentForm $form)
    {
        $this->CommentFrom = $form;
    }
    
    public function actionDefault($page, array $sort, $filter) {
        $this->template->tab = $this->_Comment->loadCommetTab();
    }

    public function actionEdit() {
        
    }
    
    protected function createComponentReplyForm()
    {
        /** @var $presenter self */
        $presenter = $this;
        return new \Nette\Application\UI\Multiplier(function ($id) use ($presenter) {
            return $presenter->CommentFrom->replyForm($id);
        }); 
    }
}