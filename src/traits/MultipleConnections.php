<?php

namespace Hiraeth\Dbal;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

/**
 *
 */
trait MultipleConnections
{
	/**
	 * @var ConnectionRegistry
	 */
	protected $registry;


	/**
	 *
	 */
	public function __construct(ConnectionRegistry $registry)
	{
		$this->registry = $registry;

		parent::__construct();
	}


	/**
	 *
	 */
	public function configure(): void
	{
		$this->addOption(
			'connection', 'c',
			InputOption::VALUE_REQUIRED,
			'The name of the connection to use',
			$this->registry->getDefaultConnectionName()
		);

		parent::configure();

		$this->setName(static::$defaultName);
		$this->setAliases([]);
	}
}
