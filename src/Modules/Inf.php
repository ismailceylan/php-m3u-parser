<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\M3U8;
use Iceylan\M3uParser\Duration;
use Iceylan\M3uParser\Support\Helper;
use Iceylan\M3uParser\Contracts\SegmentContract;

class Inf implements SegmentContract
{
	public static string $place = 'xsegments';
	public static bool $multiple = true;
	public ?string $url = null;
	public Duration $duration;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 8 ) === '#EXTINF:';
	}

	public function __construct( string $line, ?M3U8 $m3u8 )
	{
		$parts = explode( ' ', $line );
		$infPart = explode( ':', $parts[ 0 ]);

		$this->duration = new Duration( $infPart[ 1 ]);
		$this->url = Helper::normalizeURL( $parts[ 1 ], $m3u8 );
	}
}
