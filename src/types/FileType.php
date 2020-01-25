<?php

namespace Hiraeth\Dbal;

use SplFileInfo;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * A file data type for doctrine DBs
 */
class FileType extends Type
{
	const FILE = 'file';

	/**
	 *
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if (!$value) {
			return NULL;
		}

		return new SplFileInfo($value);
	}


	/**
	 *
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if (!$value instanceof SplFileInfo) {
			return NULL;
		}

		return $value->getPathname();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefaultLength(AbstractPlatform $platform)
	{
		return $platform->getVarcharDefaultLength();
	}


	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return self::FILE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
	}


	/**
	 *
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform)
	{
		return TRUE;
	}
}
