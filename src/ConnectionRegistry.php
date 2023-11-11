<?php

namespace Hiraeth\Dbal;

use Hiraeth;
use Doctrine\DBAL;
use Doctrine\Persistence;

use RuntimeException;
use InvalidArgumentException;

/**
 *
 */
class ConnectionRegistry implements Persistence\ConnectionRegistry
{
	/**
	 * @var Hiraeth\Application
	 */
	protected $app;


	/**
	 * @var string
	 */
	protected $defaultConnection;


	/**
	 * @var array<DBAL\Connection>
	 */
	protected $connections = array();


	/**
	 * @var array<string, string>
	 */
	protected $connectionConfigs = array();


	/**
	 *
	 *
	 */
	public function __construct(Hiraeth\Application $app)
	{
		$this->app               = $app;
		$this->defaultConnection = 'default';

		foreach ($app->getConfig('*', 'connection', []) as $path => $config) {
			if (!empty($config)) {
				$name = basename($path);

				if (isset($this->connectionConfigs[$name])) {
					throw new RuntimeException(sprintf(
						'Cannot add connection "%s", name already used', $name
					));
				}

				$this->connectionConfigs[$name] = $path;
			}
		}

		foreach ($this->app->getConfig('*', 'dbal.types', []) as $path => $config) {
			foreach ($config as $name => $class) {
				DBAL\Types\Type::addType($name, $class);
			}
		}

		$app->share($this);
	}


	/**
	 * {@inheritdoc}
	 *
	 * @return DBAL\Connection
	 */
	public function getConnection($name = null): DBAL\Connection
	{
		if ($name === null) {
			$name = $this->defaultConnection;
		}

		if (!isset($this->connectionConfigs[$name])) {
			throw new InvalidArgumentException(sprintf(
				'Doctrine connection named "%s" does not exist.',
				$name
			));
		}

		if (!isset($this->connections[$name])) {
			$collection = $this->connectionConfigs[$name];
			$options    = $this->app->getConfig($collection, 'connection', []) + [
				'host' => '127.0.0.1'
			];

			$options['dbname']        = $options['dbname'] ?? NULL;
			$options['password']      = $options['pass']   ?? NULL;
			$this->connections[$name] = DBAL\DriverManager::getConnection(
				empty($options['driver'])
					? ['url' => sprintf(
						'pdo-sqlite:///%s',
						$this->app->getFile('storage/db.sqlite', TRUE)
					)]
					: $options,
				$this->app->get(DBAL\Configuration::class)
			);
		}

		return $this->connections[$name];
	}


	/**
	 * @param string $name
	 * @return array<string, mixed>
	 */
	public function getConnectionConfig($name = NULL)
	{
		if (!$name) {
			$name = $this->defaultConnection;
		}

		if (!isset($this->connectionConfigs[$name])) {
			throw new InvalidArgumentException(sprintf(
				'Doctrine connection configuration named "%s" does not exist.',
				$name
			));
		}

		return $this->app->getConfig($this->connectionConfigs[$name], 'connection', []);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConnectionNames(): array
	{
		return array_combine(
			array_keys($this->connectionConfigs),
			array_map(
				function($collection) {
					return $this->app->getConfig($collection, 'connection.name', 'Unknown  Name');
				},
				$this->connectionConfigs
			));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConnections(): array
	{
		foreach ($this->connectionConfigs as $name => $collection) {
			if (!isset($this->connections[$name])) {
				$this->connections[$name] = $this->getConnection($name);
			}
		}

		return $this->connections;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getDefaultConnection(): DBAL\Connection
	{
		return $this->getConnection($this->getDefaultConnectionName());
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefaultConnectionName(): string
	{
		return $this->defaultConnection;
	}
}
