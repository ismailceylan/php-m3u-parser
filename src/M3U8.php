<?php

namespace Iceylan\M3uParser;

use Closure;

class M3U8 implements \ArrayAccess
{
	public array $segments = [];
	public array $remoteUrlParts = [];
	public float $duration = 0;
	private array $modules =
	[
		Modules\M3U::class,
		Modules\XVersion::class,
		Modules\XTargetDuration::class,
		Modules\Inf::class
	];

	public function __construct( public string $remoteURL, public ?Closure $urlBuilder )
	{
		$this->parseUrl();

		$this->getSegments(
			file_get_contents( $this->remoteURL )
		);

		$this->calculateDuration();
	}

	public function getSegments( string $raw )
	{
		// let's make sure we have everything in a single line
		$raw = preg_replace( "/(^|\n)([^\n#][^\n]*)/", ' $2', $raw );
		$lines = explode( "\n", $raw );

		foreach( $lines as $index => $line )
		{
			foreach( $this->modules as $moduleIndex => $module )
			{
				if( $module::test( $line, $index ))
				{
					$instance = new $module( $line, $this );

					if( $module::$multiple == false )
					{
						unset( $this->modules[ $moduleIndex ]);
						$this->{ $module::$name } = $instance;
					}
					else
					{
						$this->segments[] = $instance;
					}
				}
			}
		}
	}

	public function calculateDuration()
	{
		foreach( $this->segments as $segment )
		{
			$this->duration += $segment->duration;
		}
	}

	public function parseUrl()
	{
		$this->remoteUrlParts = $p = parse_url( $this->remoteURL );

		$this->remoteUrlParts[ 'absolute_path' ] = implode(
			'/',
			array_slice( explode( '/', trim( trim( $p[ 'path' ]), '/' )), 0, -1 )
		);
	}

	public function offsetExists( mixed $offset ): bool
	{
		return isset( $this->segments[ $offset ]);
	}

	public function offsetGet( mixed $offset ): mixed
	{
		return $this->segments[ $offset ];
	}

	public function offsetSet( mixed $offset, mixed $value ): void
	{
		$this->segments[ $offset ] = $value;
	}

	public function offsetUnset( mixed $offset ): void
	{
		unset( $this->segments[ $offset ]);
	}
}
