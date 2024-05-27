<?php

namespace Iceylan\M3uParser\Support;

use Iceylan\M3uParser\M3U8;

class Helper
{
	public static function normalizeURL( $url, string|M3U8 $source ): string
	{
		$urlParts = parse_url( $url );

		if( isset( $urlParts[ 'host' ]))
		{
			return $url;
		}

		$sourceParts = parse_url( $source->url );

		if( isset( $sourceParts[ 'path' ]))
		{
			$pathParts = explode( '/', $sourceParts[ 'path' ]);
			$lastPart = $pathParts[ count( $pathParts ) - 1 ];

			if( strpos( $lastPart, '.' ))
			{
				$sourceParts[ 'absolute_path' ] = implode( '/', array_slice( $pathParts, 0, -1 ));
			}
		}

		return
			$sourceParts[ 'scheme' ] . '://' .
			$sourceParts[ 'host' ] . '/' .
			( $sourceParts[ 'absolute_path' ] ?? $sourceParts[ 'path' ]) . '/' .
			$url;
	}
}
