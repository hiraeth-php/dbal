<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class StatusCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:status';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\StatusCommand::class;
}
