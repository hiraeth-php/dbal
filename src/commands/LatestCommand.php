<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class LatestCommand extends Command\LatestCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:latest';
}
