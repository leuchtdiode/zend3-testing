<?php
namespace Testing\Module;

use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use Zend\ServiceManager\ServiceManager;

trait MockTrait
{
	/**
	 * @param string $class
	 * @param string $method
	 * @param                 $result
	 * @param null|Invocation $invocation
	 * @param null $with
	 *
	 * @return MockObject
	 */
	protected function mockServiceMethod(
		string $class,
		string $method,
		$result,
		?Invocation $invocation = null,
		$with = null
	): MockObject
	{
		/** @var MockBuilder $builder */
		$builder = $this->getMockBuilder($class);

		$mock = $builder
			->disableOriginalConstructor()
			->getMock();

		/** @var ServiceManager $serviceLocator */
		$serviceLocator = $this->getApplicationServiceLocator();

		$serviceLocator->setAllowOverride(true);
		$serviceLocator->setService($class, $mock);

		$this->mockServiceAddMethod($mock, $method, $result, $invocation, $with);

		return $mock;
	}

	/**
	 * @param MockObject $mock
	 * @param string $method
	 * @param                 $result
	 * @param null|Invocation $invocation
	 * @param null $with
	 *
	 * @return MockObject
	 */
	protected function mockServiceAddMethod(
		MockObject $mock,
		string $method,
		$result,
		?Invocation $invocation = null,
		$with = null
	): MockObject
	{
		if (empty($invocation))
		{
			$invocation = $this->any();
		}

		if ($result instanceof Stub)
		{
			$stub = $result;
		}
		else
		{
			$stub = $this->returnValue($result);
		}

		$call = $mock->expects($invocation)
			->method($method)
			->will($stub);

		if (!empty($with))
		{
			$call->with($with);
		}

		return $mock;
	}
}
