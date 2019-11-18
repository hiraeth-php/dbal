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
	use MultipleConnections {
		MultipleConnections::configure as mcConfigure;
		MultipleConnections::execute as mcExecute;
	}

	/**
	 *
	 */
	public function configure(): void
	{
		$this->mcConfigure();
	}

	/**
	 *
	 */
	public function execute(InputInterface $input, OutputInterface $output): ?int
	{
		$connection_name = $input->getArgument('connection');
		$connection      = $this->registry->getConnection($connection_name);
		$config          = $this->makeMigrationConfig(
			$connection_name,
			$this->registry->getConnection($connection_name),
			$this->registry->getConnectionConfig($connection_name)
		);

		$this->setDependencyFactory(new DependencyFactory($config));
		$this->getHelperSet()->set(new ConfigurationHelper($connection, $config), 'configuration');

		return $this->mcExecute($input, $output);
	}


	/**
	 *
	 */
	protected function makeMigrationConfig($name, Connection $connection, array $data): Configuration
	{
		$config  = new Configuration($connection);
		$options = [
			'table'         => $data['migrations']['table'] ?? 'migrations',
			'directory'     => $data['migrations']['directory'] ?? '',
			'namespace'     => $data['migrations']['namespace'] ?? sprintf(
				'Migrations\%s',
				ucwords($name)
			)
		];

		$config->setName(sprintf('Migrations for "%s" Connection', $name));
		$config->setMigrationsDirectory($options['directory']);
		$config->setMigrationsNamespace($options['namespace']);
		$config->setMigrationsTableName($options['table']);
		$config->setMigrationsColumnName('version');
		$config->setMigrationsColumnLength(255);
		$config->setMigrationsExecutedAtColumnName('executed_at');
		$config->setAllOrNothing(TRUE);
		$config->setCheckDatabasePlatform(FALSE);

		return $config;
	}
}
