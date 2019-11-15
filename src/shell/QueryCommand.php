<?php

namespace Hiraeth\Dbal;

use Psy\Command\Command;
use Psy\VarDumper\Presenter;
use Psy\VarDumper\PresenterAware;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class QueryCommand extends Command implements PresenterAware
{
	/**
	 *
	 */
	public function __construct(ConnectionRegistry $registry)
	{
		$this->registry   = $registry;
		$this->connection = 'default';

		parent::__construct();
	}


	/**
	 * PresenterAware interface.
	 *
	 * @param Presenter $presenter
	 */
	public function setPresenter(Presenter $presenter)
	{
		$this->presenter = $presenter;
	}


	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('query')
			->setDescription('Execute an SQL query on a connection')
			->addArgument('sql', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The sql to execute')
			->addOption(
				'connection',
				'c',
				InputOption::VALUE_REQUIRED,
				'The name of the connection to use'
			)
			->setHelp(
<<<HELP
Execute an SQL query on a connection

query -c <connection> <sql>
HELP
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$sql = trim(implode(' ', $input->getArgument('sql')));

		if ($sql) {
			if ($input->getOption('connection')) {
				$connection = $this->registry->getConnection($input->getOption('connection'));
			} else {
				$connection = $this->registry->getConnection($this->connection);
			}

			$output->page($this->presenter->present(
				$connection->fetchAll($sql)
			));

		} else {
			if ($input->getOption('connection')) {
				$this->connection = $input->getOption('connection');
			} else {
				$output->page($this->presenter->present($this->connection));
			}
		}
	}
}
