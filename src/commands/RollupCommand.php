<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class RollupCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:rollup';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\RollupCommand::class;
}
