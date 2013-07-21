<?php
namespace FrontModule;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManager as EntityManager2;
use Models\Omptions\Facebook;
use Nette\Application\UI\Presenter;
use Nette\InvalidStateException;
/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
abstract class BasePresenter extends Presenter
{
	/** @var EntityManager */
	protected $em;
        
        /** @var Facebook */
        private $_og;

        public function injectEntityManager(EntityManager2 $em)
	{
		if ($this->em) {
			throw new InvalidStateException('Entity manager has already been set');
		}
		$this->em = $em;
                
		return $this;
	}
        
        final function injectOg(Facebook $og)
        {
            $this->_og = $og;
        }
        
        public function createComponentFacebookOg()
        {
            return $this->_og->getOpenGraphTagsControl();
        }
}
