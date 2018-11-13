<?php
namespace Testing;

use Doctrine\ORM\Tools\ToolsException;
use Testing\Dto\Creator;
use Testing\Module\DynamicFixturesTrait;
use Testing\Module\ServiceManagerTrait;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BaseTestCase extends AbstractHttpControllerTestCase
{
	use ServiceManagerTrait;
	use DynamicFixturesTrait;

	/**
	 * @throws ToolsException
	 */
	public function setUp()
	{
		$this->reset();

		$this->setApplicationConfig(
			include getcwd() . '/config/application.test.config.php'
		);

		Creator::setServiceManager(
			$this->getApplicationServiceLocator()
		);

		$this->createEmptyDb();
	}

	/**
	 * @param string $class
	 * @return mixed
	 */
	protected function getService($class)
	{
		return $this->getApplicationServiceLocator()->get($class);
	}
}