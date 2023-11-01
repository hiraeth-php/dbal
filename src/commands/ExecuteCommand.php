<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class ExecuteCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:execute';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\ExecuteCommand::class;
}
