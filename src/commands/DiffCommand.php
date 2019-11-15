<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class DiffCommand extends Command\DiffCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:diff';
}
