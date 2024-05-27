<?php

namespace Iceylan\M3uParser;

class Bandwidth
{
	public int $bps = 0;

	public function __construct( int|string $bitsPerSecond )
	{
		$this->bps = (int) $bitsPerSecond;
	}

	public function convert( array $units, int $base ): array
	{
		$current = $this->bps;

		if( $base === 1024 )
		{
			$current /= 8;
		}

		foreach( $units AS $unit )
		{
			if( $current > $base )
			{
				$current /= $base;
			}
			else
			{
				break;
			}
		}

		return [ $current, $unit ];
	}

	public function toBits( bool $longUnitNames = false ): array
	{
		$units = $longUnitNames
			? [ 'bits', 'Kilobits', 'Megabits', 'Gigabits', 'Terabits' ]
			: [ 'b', 'Kb', 'Mb', 'Gb', 'Tb' ];

		return $this->convert( $units, 1000 );
	}

	public function toBytes( bool $longUnitNames = false ): array
	{
		$units = $longUnitNames
			? [ 'bytes', 'Kilobytes', 'Megabytes', 'Gigabytes', 'Terabytes' ]
			: [ 'B', 'KB', 'MB', 'GB', 'TB' ];

		return $this->convert( $units, 1024 );
	}

	public function __toString()
	{
		[ $size, $unit ] = $this->toBytes();

		return round( $size, 2 ) . ' ' . $unit . "ps";
	}
}
