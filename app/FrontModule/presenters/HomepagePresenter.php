<?php
namespace FrontModule;

use Nette\Application\Responses\TextResponse;
use PDOException;
use User;
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
        //$post = $this->em->getRepository('Models\Entity\Post\Post');
        //$user = $this->em->getRepository('Models\Entity\Menu\Menu');
        //dump($post);
        //dump($user);
    }
    
    public function handleCreateDefaultUser()
    {

        $pass = \Models\Authenticator\Authenticator::staticHash('admin');
        
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

        $this->sendResponse(new \Nette\Application\Responses\TextResponse('OK'));
        $this->terminate();
    }
}
