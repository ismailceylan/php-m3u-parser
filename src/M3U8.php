<?php

namespace Iceylan\M3uParser;

use Closure;

/**
 * It parses an M3U8 playlist.
 * 
 * @property array $xsegments
 */
class M3U8 implements \ArrayAccess
{
	public Duration $duration;
	public bool $isMaster = false;

	public function __construct( public string $url )
	{
		$this->getSegments(
			$this->downloadM3UFile( $this->url )
		);

		$this->calculateDuration();

		$this->isMaster = empty( $this->xsegments );
	}

	public function downloadM3UFile( string $url ): string
	{
		$content = @file_get_contents( $url );

		if( $content === false )
		{
			throw new \Exception( 'M3U file could not be downloaded!' );
		}

		return $content;
	}

	public function getSegments( string $raw )
	{
		// let's make sure we have everything in a single line
		$raw = preg_replace( "/(^|\n)([^\n#][^\n]*)/", ' $2', $raw );
		$lines = explode( "\n", $raw );

		$modules =
		[
			Modules\M3U::class,
			Modules\XVersion::class,
			Modules\XTargetDuration::class,
			Modules\XStreamInf::class,
			Modules\Inf::class,
		];

		foreach( $lines as $index => $line )
		{
			foreach( $modules as $moduleIndex => $module )
			{
				$this->initModulePlaceholder( $module );
				
				if( $module::test( $line, $index ))
				{
					$instance = new $module( $line, $this );

					if( $module::$multiple )
					{
						$this->{ $module::$place }[] = $instance;
					}
					else
					{
						$this->{ $module::$place } = $instance;
						unset( $modules[ $moduleIndex ]);
					}
				}
			}
		}
	}

	private function initModulePlaceholder( $module ): void
	{
		if( $module::$multiple )
		{
			if( ! property_exists( $this, $module::$place ))
			{
				$this->{ $module::$place } = [];
			}
		}
		else
		{
			$this->{ $module::$place } = null;
		}
	}

	public function calculateDuration()
	{
		$this->duration = new Duration( 0.0 );
		
		foreach( @$this->xsegments ?? [] as $segment )
		{
			$this->duration->add( $segment->duration );
		}
	}

	public function __toString()
	{
		// dd( $this);
		if( $this->isMaster )
		{

		}

		$segments = array_map( function( $segment )
		{
			$path = explode( '/', parse_url( $segment->url )[ 'path' ]);
			$filename = array_pop( $path );

			return "#EXTINF:{$segment->duration->seconds},\r\n{$filename}";
		}, $this->xsegments );

		$segments = implode( "\r\n", $segments );

		return <<<M3U
		#EXTM3U
		#EXT-X-TARGETDURATION:{$this->xduration->duration->seconds}
		#EXT-X-ALLOW-CACHE:YES
		#EXT-X-PLAYLIST-TYPE:VOD
		#EXT-X-VERSION:{$this->xversion->version}
		#EXT-X-MEDIA-SEQUENCE:1
		$segments
		#EXT-X-ENDLIST
		M3U;
	}

	public function offsetExists( mixed $offset ): bool
	{
		return isset( $this->xsegments[ $offset ]);
	}

	public function offsetGet( mixed $offset ): mixed
	{
		return $this->xsegments[ $offset ];
	}

	public function offsetSet( mixed $offset, mixed $value ): void
	{
		$this->xsegments[ $offset ] = $value;
	}

	public function offsetUnset( mixed $offset ): void
	{
		unset( $this->xsegments[ $offset ]);
	}
}
