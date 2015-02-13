<?php
namespace FrontModule;

use Models\Authenticator\Authenticator;
use Models\Entity\User;
use Nette\Application\Responses\TextResponse;
use PDOException;
/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends BasePresenter
{
    public function actionCreateDefaultUser()
    {
            $user = new User('admin');
            $user->setPassword($this->getContext()->authenticator->calculateHash('traktor'));
            $user->setEmail('info@nella-project.org')->setRole('admin');

            $this->em->persist($user);
            try {
                    $this->em->flush();
            } catch(PDOException $e) {
                    dump($e);
                    $this->terminate();
            }

            $this->sendResponse(new TextResponse('OK'));
            $this->terminate();
    }

    public function renderDefault()
    {
        
        //$this->template->pageOptions = $this->_pageService->getPageControl();
    }
    
    public function handleCreateDefaultUser()
    {

        $pass = Authenticator::staticHash('admin');
        
        $user = new \Models\Entity\User\User('admin');
        $user->setPassword($pass);
        $user->setEmail('admin@admin.cz');
        $user->setRole('admin');
        $this->em->persist($user);
        try {
                $this->em->flush();
        } catch(\PDOException $e) {
                dump($e);
                $this->terminate();
        }

        $this->sendResponse(new TextResponse('OK'));
        $this->terminate();
    }
}
