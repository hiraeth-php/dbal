<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class MigrateCommand extends Command\MigrateCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:migrate';
}
