<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;

class Inf implements SegmentContract
{
	public static string $name = 'Inf';
	public static bool $multiple = true;
	public float $duration = 0;
	public ?string $url = null;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 8 ) === '#EXTINF:';
	}

	public function __construct( string $line )
	{
		$parts = explode( ' ', $line );
		$infPart = explode( ':', $parts[ 0 ]);

		$this->duration = (float) trim( trim( $infPart[ 1 ], ',' ));
		$this->url = $parts[ 1 ];
	}
}
