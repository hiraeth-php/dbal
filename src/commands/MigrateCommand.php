<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class MigrateCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:migrate';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\MigrateCommand::class;
}
