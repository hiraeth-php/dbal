<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 * Proxy command cause doctrine migrations is a huge pile of shit
 */
class DumpSchemaCommand extends AbstractCommand
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultName = 'migrations:dump-schema';

	/**
	 * {@inheritDoc}
	 */
	static protected $proxy = Command\DumpSchemaCommand::class;
}
