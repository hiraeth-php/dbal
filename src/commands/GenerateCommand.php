<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class GenerateCommand extends Command\GenerateCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:generate';
}
