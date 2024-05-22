<?php

namespace Iceylan\PhpStreamDownloader\Modules;

use Iceylan\PhpStreamDownloader\Contracts\SegmentContract;
use Iceylan\PhpStreamDownloader\Exceptions\InvalidFileFormatException;

class ExtM3u implements SegmentContract
{
	public static $matched = false;

	public function __construct( string $line )
	{
		
	}

	public static function test( string $line, int $index ): bool
	{
		if( self::$matched )
		{
			return false;
		}
		
		$isPassed = trim( $line ) == '#EXTM3U';

		if( $index === 0 && ! $isPassed )
		{
			throw new InvalidFileFormatException(
				'First segment must be #EXTM3U but was not found!'
			);
		}

		self::$matched = true;

		return $isPassed;
	}
}
