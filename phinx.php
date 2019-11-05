<?php

require('vendor/autoload.php');

if (!file_exists(__DIR__ . '/.env')) {
	throw new RuntimeExcpetion('Cannot find .env file, make sure you are in the right path');
}

$parser  = new Dotink\Jin\Parser();
$env     = $parser->parse(file_get_contents(__DIR__ . '/.env'));
$adapter = function($driver) {
	$translations = [
		'pgsql'      => 'pgsql',
		'mysql'      => 'mysql',
		'sqlsrv'     => 'sqlsrv',
		'sqlite'     => 'sqlite',
		'pdo_pgsql'  => 'pgsql',
		'pdo_mysql'  => 'mysql',
		'pdo_sqlsrv' => 'sqlsrv',
		'pdo_sqlite' => 'sqlite'
	];

	if (!$driver) {
		return NULL;
	}

	if (!isset($translations[$driver])) {
		throw new RuntimeException(sprintf(
			'Unsupported adapter "%s"',
			$driver
		));
	}

	return $translations[$driver];
};

return [
	'paths' => [
		'migrations' => __DIR__ . '/database/migrations',
		'seeds'      => __DIR__ . '/database/seeds'
	],
	'environments' => [
		'default_migration_table' => 'dbal_migrations',
		'default_database' => 'current',
		'current' => [
			'adapter' => $adapter($env->get('DATABASES.DEFAULT.TYPE')),
			'host'    => $env->get('DATABASES.DEFAULT.HOST') ?: 'localhost',
			'user'    => $env->get('DATABASES.DEFAULT.USER') ?: 'web',
			'pass'    => $env->get('DATABASES.DEFAULT.PASS'),
			'name'    => $env->get('DATABASES.DEFAULT.NAME'),
		]
	]
];

