<?php

namespace Iceylan\PhpStreamDownloader;

class M3U8
{
	public array $segments = [];
	public array $modules =
	[
		Modules\ExtM3u::class,
		Modules\ExtXVersion::class,
	];

	public function __construct( public string $remoteURL )
	{
		$this->getSegments(
			file_get_contents( $this->remoteURL )
		);
	}

	public function getSegments( string $raw )
	{
		// let's make sure we have everything in a single line
		$raw = str_replace( ",\n", ", ", $raw );
		$lines = explode( "\n", $raw );

		foreach( $lines as $index => $line )
		{
			foreach( $this->modules as $module )
			{
				if( $module::test( $line, $index ))
				{
					$this->segments[] = new $module( $line );
				}
			}

			if( $index > 10 ) break;
		}

		var_dump( $this );
	}
}
