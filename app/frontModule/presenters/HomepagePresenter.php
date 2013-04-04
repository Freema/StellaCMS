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

    /**
     * @var \TagFacade
     */
    private $_tagService;
    
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


    final function injectTagModel(\TagFacade $tag)
    {
        $this->_tagService = $tag;
    }

    public function renderDefault()
    {
        $post = $this->em->getRepository('Tag')->findByName('jjj');
        dump($post);
    }

}
