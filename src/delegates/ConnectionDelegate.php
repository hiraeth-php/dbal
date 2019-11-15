<?php

namespace Hiraeth\Dbal;

use Hiraeth;
use Doctrine\DBAL\Connection;

/**
 *
 */
class ConnectionDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return Connection::class;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		return $app->get(ConnectionRegistry::class)->getConnection();
	}
}
