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
	 * {@inheritDoc}
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform): mixed
	{
		if (!$value) {
			return NULL;
		}

		return new SplFileInfo($value);
	}


	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
	{
		if (!$value instanceof SplFileInfo) {
			return NULL;
		}

		return $value->getPathname();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getDefaultLength(AbstractPlatform $platform): int
	{
		return 255;
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
	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
	{
		return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
	}


	/**
	 * {@inheritDoc}
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return TRUE;
	}
}
