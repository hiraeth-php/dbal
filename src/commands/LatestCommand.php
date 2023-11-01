<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class LatestCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:latest';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\LatestCommand::class;
}
