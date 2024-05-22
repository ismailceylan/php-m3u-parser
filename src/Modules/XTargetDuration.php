<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;

class XTargetDuration implements SegmentContract
{
	public static string $name = 'XTargetDuration';
	public static bool $multiple = false;
	public float $duration = 0.0;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 22 ) === '#EXT-X-TARGETDURATION:';
	}

	public function __construct( string $line )
	{
		$this->duration = (float) explode( ':', $line )[ 1 ];
	}
}
