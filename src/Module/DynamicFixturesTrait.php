<?php
namespace Testing\Module;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Testing\Dto\CreationResult;
use Zend\Mvc\Application;

trait DynamicFixturesTrait
{
	/**
	 * @throws ToolsException
	 */
	private function createEmptyDb(): void
	{
		$db = __DIR__ . '/../../../../data/testing/test.sqlite';

		if (!file_exists($db))
		{
			touch($db);
		}
		else
		{
			unlink($db);
		}

		/** @var Application $app */
		$app = $this->getApplication();

		/** @var EntityManager $em */
		$em = $app->getServiceManager()->get(EntityManager::class);

		$metaData = $em->getMetadataFactory()->getAllMetadata();
		$schema   = new SchemaTool($em);
		$schema->createSchema($metaData);
	}

	/**
	 * @param array $entities
	 * @param bool $clearUnitOfWork
	 * @throws \Doctrine\Common\Persistence\Mapping\MappingException
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	protected function fillDb(array $entities, bool $clearUnitOfWork = false)
	{
		/** @var EntityManager $entityManager */
		$entityManager = $this->getInstance(EntityManager::class);

		foreach ($entities as $entity)
		{
			if ($entity instanceof CreationResult)
			{
				$entity = $entity->getEntity();
			}

			$entityManager->persist($entity);
		}

		$entityManager->flush();

		/*
		 * reset the unit of work to have the same conditions as if nothing ever happened
		 */
		if ($clearUnitOfWork)
		{
			$entityManager->clear();
		}
	}
}
