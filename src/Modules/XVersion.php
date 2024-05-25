<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;

class XVersion implements SegmentContract
{
	public static string $place = 'xversion';
	public static bool $multiple = false;
	public ?int $version = null;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 15 ) === '#EXT-X-VERSION:';
	}

	public function __construct( string $line )
	{
		[ , $this->version ] = explode( ':', $line );
	}
}
