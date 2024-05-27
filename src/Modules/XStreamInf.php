<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\M3U8;
use Iceylan\M3uParser\Bandwidth;
use Iceylan\M3uParser\FrameRate;
use Iceylan\M3uParser\Resolution;
use Iceylan\M3uParser\Support\Helper;
use Iceylan\M3uParser\Contracts\SegmentContract;

class XStreamInf implements SegmentContract
{
	public ?M3U8 $m3u;
	public FrameRate $frames;
	public Bandwidth $bandwidth;
	public Resolution $resolution;
	public array $info = [];
	public static bool $multiple = true;
	public static string $place = "xstreams";

	public static function test( string $line, int $lineNumber ): bool
	{
		return substr( ltrim( $line ), 0, 18 ) === '#EXT-X-STREAM-INF:';
	}

	public function __construct( string $line, M3U8 $parent )
	{
		$mainParts = explode( ' ', $line );
		$infoParts = explode( ':', $mainParts[ 0 ]);

		$uri = Helper::normalizeURL( trim( $mainParts[ 1 ]), $parent );

		parse_str( str_replace( ',', '&', $infoParts[ 1 ]), $this->info );

		$this->m3u = new M3U8( $uri );
		$this->frames = new FrameRate( $this->info[ 'FRAME-RATE' ]);
		$this->bandwidth = new Bandwidth( $this->info[ 'BANDWIDTH' ]);
		$this->resolution = new Resolution( $this->info[ 'RESOLUTION' ]);
	}
}
