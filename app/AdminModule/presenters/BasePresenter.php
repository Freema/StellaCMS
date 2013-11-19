<?php
namespace AdminModule;

use AdminModule\Forms\BaseForm;
use Components\Breadcrumbs\Breadcrumbs;
use Doctrine\ORM\EntityManager;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Nette\Forms\Controls\BaseControl;
use Nette\InvalidStateException;
use Nette\Application\UI\Form;


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
            if($this->isAjax())
            {
                $template = $this->template;
                $template->setFile(__DIR__ . '\..\templates\Error\accessModalAjax.latte');
                $template->render();
                $this->terminate();
            }
            else
            {
                $this->redirect('Login:default');
            }
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
        return new Breadcrumbs($this);
    }
    
   /**
    * Message box pro chybné odpovědi ze serveru.
    * @callback
    * @param BaseForm $form
    */
   public function formMessageErrorResponse(Form $form)
   {
        $snippet = BaseForm::getInfoBox($form->getErrors(), "danger");
        $this->sendResponse(new JsonResponse(array(
                'status'    => 'error',
                'errors'    => $form->getErrors(),
                'snippet'   => $snippet,
         )));         
   }    
        
}
