<?php

namespace Hiraeth\Dbal;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

/**
 *
 */
trait MultipleConnections
{
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
	public function configure()
	{
		$this->addArgument('connection', InputArgument::OPTIONAL, 'The name of the connection to use');

		return parent::configure();
	}


	/**
	 *
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$connection = $input->getArgument('connection');

		if ($connection) {
			$this->getHelperSet()->set(
				new ConnectionHelper($this->registry->getConnection($connection)),
				'db'
			);
		}

		return parent::execute($input, $output);
	}
}
