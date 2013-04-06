<?php
namespace AdminModule;
use Nette\Application\UI,
    Nette\Security as NS;


/**
 * Sign in/out presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class LoginPresenter extends BasePresenter
{
    /**
     *
     * @var Forms\LoginForm
     */
    private $_loginForm;

    public function injectLoginForm(Forms\LoginForm $factory)
    {
        $this->_loginForm = $factory;
    }

    /**
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm()
    {
        return $this->_loginForm->creatForm();;
    }
    
    public function renderDefault()
    {
    }

}
