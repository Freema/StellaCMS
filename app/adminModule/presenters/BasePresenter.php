<?php
namespace AdminModule;

use Nette\Application\UI\Presenter;

/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
abstract class BasePresenter extends Presenter
{

    public function actionOut()
    {
            $this->getUser()->logout();
            $this->flashMessage('You have been signed out.');
            $this->redirect('in');
    }
        
}
