<?php

namespace Iceylan\PhpStreamDownloader\Modules;

use Iceylan\PhpStreamDownloader\Contracts\SegmentContract;

class ExtXVersion implements SegmentContract
{
	public static $matched = false;
	public string $version = '';

	public static function test( string $line, int $lineNumber ): bool
	{
		if( self::$matched )
		{
			return false;
		}

		$isPassed = substr( ltrim( $line ), 0, 14 ) === '#EXT-X-VERSION';

		if( $isPassed )
		{
			self::$matched = true;
		}

		return $isPassed;
	}

	public function __construct( string $line )
	{
		[ , $this->version ] = explode( ':', $line );
	}
}
