<?php
namespace AdminModule\Forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

/**
 * Description of LoginForm
 *
 * @author Tomáš Grasl
 */
class LoginForm extends BaseForm {
    
    /**
    * Sign in form component factory.
    * @return Form
    */
    public function creatForm()
    {
            $form = new Form;
            $form->addText('username', 'Username:')
                 ->setRequired('Please provide a username.')
                 ->setAttribute("placeholder", "Uživatelské jméno");

            $form->addPassword('password', 'Password:')
                 ->setRequired('Please provide a password.')
                 ->setAttribute("placeholder", "Heslo");

            $form->addCheckbox('persistent', 'Pamatovat si mě');

            $form->addSubmit('send', 'Sign in');

            $form->onSuccess[] = callback($this, 'signInFormSubmitted');
            
            $Btn = $form['send']->getControlPrototype();
            $Btn->setName("button");
            $Btn->type = 'submit'; 
            $Btn->add(' Přihlásit se');
            
            return $form;
    }

    public function signInFormSubmitted(Form $form)
    {
        try {
                $values = $form->getValues();
                if ($values->persistent) {
                    $form->presenter->getUser()->setExpiration('+30 days', FALSE);
                }
                $form->presenter->getUser()->login($values->username, $values->password);
                $form->presenter->redirect('ControlPanel:default');

        } catch (AuthenticationException $e) {
                $form->addError($e->getMessage());
        }
    }
    
    
}
