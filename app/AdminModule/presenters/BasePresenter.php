<?php
namespace AdminModule;

use Nette\Application\UI\Presenter;
use Nette\Forms\Controls\BaseControl;

BaseControl::$idMask = '%2$s';

abstract class BasePresenter extends Presenter
{
    
    protected function startup()
    {
        parent::startup();

        if(!($this->getUser()->isLoggedIn()))
        {
            $this->redirect('Login:default');
        }
    }


    public function handleLogout()
    {
            $this->getUser()->logout();
            $this->flashMessage('You have been signed out.');
            $this->redirect('Login:default');
    }
        
}
