<?php
namespace AdminModule\Forms;

use Nette\Application\UI\Form;

/**
 * Description of LoginForm
 *
 * @author Tomáš
 */
class LoginForm extends BaseForm {
    
    public function __construct() {
    }
    
    /**
    * Sign in form component factory.
    * @return Form
    */
    public function creatForm()
    {
            $form = new Form;
            $form->addText('username', 'Username:')
                    ->setRequired('Please provide a username.');

            $form->addPassword('password', 'Password:')
                    ->setRequired('Please provide a password.');

            $form->addCheckbox('persistent', 'Remember me on this computer');

            $form->addSubmit('send', 'Sign in');

            $form->onSuccess[] = callback($this, 'signInFormSubmitted');
            
            $Btn = $form['send']->getControlPrototype();
            $Btn->setName("button");
            $Btn->type = 'submit'; 
            $Btn->create('i class="icon-ok-sign"');
            $Btn->add(' Přihlásit se');
            
            
            return $form;
    }

    public function signInFormSubmitted(Form $form)
    {
        try {
                $values = $form->getValues();
                if ($values->persistent) {
                         $form->presenter->getUser()->setExpiration('+ 14 days', FALSE);
                } else {
                         $form->presenter->getUser()->setExpiration('+ 20 minutes', TRUE);
                }
                $form->presenter->getUser()->login($values->username, $values->password);
                $form->presenter->redirect('ControlPanel:default');

        } catch (NS\AuthenticationException $e) {
                $form->addError($e->getMessage());
        }
    }
    
    
}
