<?php

namespace Hiraeth\Dbal;

use Hiraeth;
use RuntimeException;
use InvalidArgumentException;
use Doctrine\Common\Persistence;
use Doctrine\DBAL;

/**
 *
 */
class ConnectionRegistry implements Persistence\ConnectionRegistry
{
	/**
	 *
	 */
	protected $app = NULL;


	/**
	 *
	 */
	protected $defaultConnection = NULL;


	/**
	 *
	 */
	protected $connections = array();


	/**
	 *
	 */
	protected $connectionCollections = array();


	/**
	 *
	 */
	protected $name = NULL;


	/**
	 *
	 */
	protected $types = array();


	/**
	 *
	 *
	 */
	public function __construct(Hiraeth\Application $app)
	{
		$this->app               = $app;
		$this->name              = 'default';
		$this->defaultConnection = 'default';

		foreach ($app->getConfig('*', 'connection', []) as $path => $config) {
			if (!empty($config)) {
				$name = basename($path);

				if (isset($this->connectionCollections[$name])) {
					throw new RuntimeException(sprintf(
						'Cannot add connection "%s", name already used', $name
					));
				}

				$this->connectionCollections[$name] = $path;
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
	 */
	public function getConnection($name = null): object
	{
		if ($name === null) {
			$name = $this->defaultConnection;
		}

		if (!isset($this->connectionCollections[$name])) {
			throw new InvalidArgumentException(sprintf('Doctrine %s Connection named "%s" does not exist.', $this->name, $name));
		}

		if (!isset($this->connections[$name])) {
			$collection = $this->connectionCollections[$name];
			$options    = $this->app->getConfig($collection, 'connection', []) + [
				'host' => '127.0.0.1'
			];

			$this->connections[$name] = DBAL\DriverManager::getConnection(
				empty($options['driver']) ? ['url'=>'sqlite:///:memory:'] : $options,
				$this->app->get(DBAL\Configuration::class)
			);
		}

		return $this->connections[$name];
	}


	/**
	 *
	 */
	public function getConnectionConfig($name = NULL)
	{
		if (!$name) {
			$name = $this->defaultConnection;
		}

		if (!isset($this->connectionCollections[$name])) {
			throw new InvalidArgumentException(sprintf('Doctrine %s Connection named "%s" does not exist.', $this->name, $name));
		}

		return $this->app->getConfig($this->connectionCollections[$name], 'connection', []);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConnectionNames(): array
	{
		return array_keys($this->connectionCollections);
	}


	/**
	 * {@inheritdoc}
	 */
	public function getConnections(): array
	{
		foreach ($this->connectionCollections as $name => $collection) {
			if (!isset($this->connections[$name])) {
				$this->connections[$name] = $this->getConnection($name);
			}
		}

		return $this->connections;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefaultConnectionName(): string
	{
		return $this->defaultConnection;
	}


	/**
	 * Gets the name of the registry.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
}
