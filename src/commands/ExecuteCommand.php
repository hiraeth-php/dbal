<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class ExecuteCommand extends Command\ExecuteCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:execute';
}
