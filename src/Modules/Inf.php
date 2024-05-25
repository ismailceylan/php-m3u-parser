<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;
use Iceylan\M3uParser\M3U8;

class Inf implements SegmentContract
{
	public static string $place = 'xsegments';
	public static bool $multiple = true;
	public float $duration = 0;
	public ?string $url = null;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 8 ) === '#EXTINF:';
	}

	public function __construct( string $line, ?M3U8 $m3u8 )
	{
		$parts = explode( ' ', $line );
		$infPart = explode( ':', $parts[ 0 ]);

		$this->duration = (float) trim( trim( $infPart[ 1 ], ',' ));
		$this->url = $m3u8->urlBuilder !== null
			? ( $m3u8->urlBuilder )( $m3u8->remoteUrlParts, $parts[ 1 ])
			: $parts[ 1 ];
	}
}
