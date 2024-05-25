<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;
use Iceylan\M3uParser\M3U8;

class XStreamInf implements SegmentContract
{
	public static string $place = "xstreams";
	public static bool $multiple = true;
	public array $info = [];
	public ?M3U8 $m3u;

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 18 ) === '#EXT-X-STREAM-INF:';
	}

	public function __construct( string $line, M3U8 $parent )
	{
		$mainParts = explode( ' ', $line );
		$infoParts = explode( ':', $mainParts[ 0 ]);

		$uri = ( $parent->urlBuilder )(
			$parent->remoteUrlParts,
			trim( $mainParts[ 1 ])
		);

		parse_str( str_replace( ',', '&', $infoParts[ 1 ]), $this->info );

		$this->m3u = new M3U8( $uri, $parent->urlBuilder );
	}
}
