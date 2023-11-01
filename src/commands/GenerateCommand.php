<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class GenerateCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:generate';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\GenerateCommand::class;
}
