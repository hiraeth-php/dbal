<?php

namespace Hiraeth\Dbal;

use Hiraeth;
use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;

/**
 *
 */
class MigrationConfigurationHelperDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return ConfigurationHelper::class;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$connection = $app->get(Connection::class);
		$config     = new Configuration($connection);

		$config->setMigrationsNamespace('Migrations');
		$config->setMigrationsDirectory($app->getDirectory('database/migrations')->getRealPath());

		return new ConfigurationHelper($connection, $config);
	}
}
