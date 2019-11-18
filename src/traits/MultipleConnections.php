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
	public function configure(): void
	{
		parent::configure();

		$this->addOption(
			'connection',
			'c'
			InputArgument::OPTIONAL,
			'The name of the connection to use',
			$this->registry->getDefaultConnectionName()
		);
	}


	/**
	 *
	 */
	public function execute(InputInterface $input, OutputInterface $output): ?int
	{
		$connection = $input->getArgument('connection');

		$this->getHelperSet()->set(
			new ConnectionHelper($this->registry->getConnection($connection)),
			'db'
		);

		return parent::execute($input, $output);
	}
}
