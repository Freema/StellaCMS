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
    private $_Commet;
    
    /**
     * @var \Models\Post\Post 
     */
    private $_Post;

    final function injectCommentService(\Models\Comment\Comment $service)
    {
        $this->_Commet = $service;
    }
    
    final function injectPostService(\Models\Post\Post $service)
    {
        $this->_Post = $service;
    }
    
    public function actionDefault() {
        
    }

    public function actionEdit() {
        
    }
    
    protected function createComponentComments()
    {
        $post = $this->_Post->getPostRepository()->getOne(34);
        return new \Components\Comments\Comments($this->_Commet, $post);
    }

}