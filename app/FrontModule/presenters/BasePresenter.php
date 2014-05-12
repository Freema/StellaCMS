<?php
namespace FrontModule;

use Kdyby\Doctrine\EntityManager;
use Models\Omptions\Facebook;
use Models\Omptions\Page;
use Nette\Application\UI\Presenter;
use Nette\InvalidStateException;
/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
abstract class BasePresenter extends Presenter {
    
    /** @var EntityManager */
    protected $em;

    /** @var Page */
    protected $_pageService;

    /** @var Facebook */
    private $_og;

    public function injectEntityManager(EntityManager $em) {
        if ($this->em) {
                throw new InvalidStateException('Entity manager has already been set');
        }
        $this->em = $em;

        return $this;
    }

    /*
    final function injectOg(Facebook $og) {
        $this->_og = $og;
    }

    final function injectPageOptionsService(Page $service) {
        $this->_pageService = $service;
    }

    public function createComponentFacebookOg() {
        return $this->_og->getOpenGraphTagsControl();
    }
     * 
     */
}
