<?php
namespace AdminModule;

use Nette\Application\UI\Presenter;
use Nette\Forms\Controls\BaseControl;

use Nette\InvalidStateException;
use Doctrine\ORM\EntityManager;

BaseControl::$idMask = '%2$s';

abstract class BasePresenter extends Presenter
{

    /** @var EntityManager */
    protected $_em;
    
    protected function startup()
    {
        parent::startup();

        if(!($this->getUser()->isLoggedIn()))
        {
            $this->redirect('Login:default');
        }
    }

    public function injectEntityManager(EntityManager $em)
    {
            if ($this->_em) {
                    throw new InvalidStateException('Entity manager has already been set');
            }
            $this->_em = $em;

            return $this;
    }    

    public function handleLogout()
    {
            $this->getUser()->logout();
            $this->flashMessage('You have been signed out.');
            $this->redirect('Login:default');
    }
    
    public function createComponentBreadCrumbs()
    {
        return new \Components\Breadcrumbs\Breadcrumbs($this);
    }
        
}
