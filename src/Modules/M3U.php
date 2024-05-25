<?php

namespace Iceylan\M3uParser\Modules;

use Iceylan\M3uParser\Contracts\SegmentContract;
use Iceylan\M3uParser\Exceptions\InvalidFileFormatException;

class M3U implements SegmentContract
{
	public static string $place = 'm3u';
	public static bool $multiple = false;

	public static function test( string $line, int $index ): bool
	{
		$isPassed = substr( trim( $line ), 0, 7 ) == '#EXTM3U';

		if( $index === 0 && ! $isPassed )
		{
			throw new InvalidFileFormatException(
				'First segment must be #EXTM3U but was not found!'
			);
		}

		return $isPassed;
	}
	
	public function __construct( string $line )
	{
		
	}

}
