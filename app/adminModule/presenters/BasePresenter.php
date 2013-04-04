<?php
namespace AdminModule;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManager as EntityManager2;
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

	public function injectEntityManager(EntityManager2 $em)
	{
		if ($this->em) {
			throw new InvalidStateException('Entity manager has already been set');
		}
		$this->em = $em;
		return $this;
	}
}
