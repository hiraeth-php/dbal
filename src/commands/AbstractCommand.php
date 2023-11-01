<?php

namespace Hiraeth\Dbal;

use Hiraeth\Console;
use Hiraeth\Dbal\ConnectionRegistry;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Console\ProxyCommand
{
	/**
	 * @var array<string>
	 */
	static protected $excludeOptions = [
		'db-configuration',
		'configuration',
		'conn',
		'em',
	];


	/**
	 * @var array<string>
	 */
	static protected $excludePassthruOptions = [
		'connection'
	];


	/**
	 * @var ConnectionRegistry
	 */
	protected $connections;

	/**
	 *
	 */
	public function __construct(ConnectionRegistry $connections)
	{
		$this->connections = $connections;

		parent::__construct();
	}

	/**
	 *
	 */
	protected function configure(): void
	{
		$this
			->addOption(
				'connection', 'c',
				InputOption::VALUE_OPTIONAL,
				'The name of the connection to use',
				$this->connections->getDefaultConnectionName()
			)
		;

		parent::configure();
	}

	/**
	 *
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$name          = $input->getOption('connection');
		$connection    = $this->connections->getConnection($name);
		$configuration = new Configuration();
		$meta_data     = new TableMetadataStorageConfiguration();
		$options       =
		(
			$this->connections->getConnectionConfig($name)['migrations'] ?? array()
		) + [
			'table'     => 'migrations',
			'namespace' => 'Migrations',
			'directory' => sprintf('database/%s/migrations', $name),
		];

		$meta_data->setTableName($options['table']);
		$meta_data->setVersionColumnName('version');
		$meta_data->setVersionColumnLength(255);
		$meta_data->setExecutedAtColumnName('executed_at');

		$configuration->setAllOrNothing(TRUE);
		$configuration->setMetadataStorageConfiguration($meta_data);
		$configuration->addMigrationsDirectory($options['namespace'], $options['directory']);
		$configuration->setCheckDatabasePlatform(FALSE);

		$connection->getConfiguration()->setSchemaAssetsFilter(NULL);

		$command  = new static::$proxy(DependencyFactory::fromConnection(
			new ExistingConfiguration($configuration),
			new ExistingConnection($connection)
		));

		return $command->run($this->passthru($input), $output);
	}
}
