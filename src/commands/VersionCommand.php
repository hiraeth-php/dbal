<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class VersionCommand extends Command\VersionCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:version';
}
