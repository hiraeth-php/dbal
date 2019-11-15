<?php

namespace Hiraeth\Dbal;

use Doctrine\Migrations\Tools\Console\Command;

/**
 *
 */
class DumpSchemaCommand extends Command\DumpSchemaCommand
{
	use MigrationConfig;

	protected static $defaultName = 'migrations:dump-schema';
}
