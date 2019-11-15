<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class StatusCommand extends Command\StatusCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:status';
}
