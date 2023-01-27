<?php

namespace Hiraeth\Dbal;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\Migrations\DependencyFactory;

/**
 *
 */
trait MigrationConfig
{
	use MultipleConnections;

	/**
	 * @param string $name
	 * @param array<string, mixed> $data
	 */
	protected function getMigrationConfiguration(InputInterface $input, OutputInterface $output): Configuration
	{
		$name          = $input->getOption('connection');
		$connection    = $this->registry->getConnection($name);
		$configuration = new Configuration($connection);
		$options       =
		(
			$this->registry->getConnectionConfig($name)['migrations'] ?? array()
		) + [
			'table'     => 'migrations',
			'directory' => sprintf('database/%s/migrations', $name),
			'namespace' => 'Migrations'
		];

		$connection->getConfiguration()->setSchemaAssetsFilter(NULL);

		$configuration->setName(sprintf('Migrations for "%s" Connection', $name));
		$configuration->setMigrationsDirectory($options['directory']);
		$configuration->setMigrationsNamespace($options['namespace']);
		$configuration->setMigrationsTableName($options['table']);
		$configuration->setMigrationsColumnName('version');
		$configuration->setMigrationsColumnLength(255);
		$configuration->setMigrationsExecutedAtColumnName('executed_at');
		$configuration->setAllOrNothing(TRUE);
		$configuration->setCheckDatabasePlatform(FALSE);

		return $configuration;
	}
}
