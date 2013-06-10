<?php
namespace AdminModule;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class LoginPresenter extends Presenter
{
    /**
     *
     * @var Forms\LoginForm
     */
    private $_loginForm;
    
    final function injectLoginForm(Forms\LoginForm $factory)
    {
        $this->_loginForm = $factory;
    }
    
    public function startup() {
        parent::startup();
        
        if($this->getUser()->isLoggedIn())
        {
            $this->redirect('ControlPanel:default');
        }
        
    }

    /**
     * @return Form
     */
    protected function createComponentSignInForm()
    {
        return $this->_loginForm->creatForm();;
    }
    
    public function renderDefault()
    {
    }

}
