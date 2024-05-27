<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;
use Iceylan\M3uParser\Duration;

class XTargetDuration implements SegmentContract
{
	public static string $place = 'xduration';
	public static bool $multiple = false;
	public Duration $duration;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 22 ) === '#EXT-X-TARGETDURATION:';
	}

	public function __construct( string $line )
	{
		$this->duration = new Duration( explode( ':', $line )[ 1 ]);
	}
}
