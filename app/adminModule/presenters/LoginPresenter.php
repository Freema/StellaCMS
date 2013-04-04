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

    protected function createComponentSignInForm()
    {
        $form = $this->_loginForm->creatForm();
        return $form;
    }

    public function signInFormSubmitted($form)
    {
            try {
                    $values = $form->getValues();
                    if ($values->remember) {
                            $this->getUser()->setExpiration('+ 14 days', FALSE);
                    } else {
                            $this->getUser()->setExpiration('+ 20 minutes', TRUE);
                    }
                    $this->getUser()->login($values->username, $values->password);
                    $this->redirect('Homepage:');

            } catch (NS\AuthenticationException $e) {
                    $form->addError($e->getMessage());
            }
    }
    
    public function renderDefault()
    {
    }



    public function actionOut()
    {
            $this->getUser()->logout();
            $this->flashMessage('You have been signed out.');
            $this->redirect('in');
    }

}
