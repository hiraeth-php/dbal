<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class RollupCommand extends Command\RollupCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:rollup';
}
