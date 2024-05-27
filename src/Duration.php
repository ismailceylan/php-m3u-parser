<?php

namespace Iceylan\M3uParser;

class Duration
{
	public float $seconds = 0.0;

	public function __construct( string|int|float $duration = 0 )
	{
		$this->seconds = (float) trim( trim( $duration, ',' ));
	}

	public function __toString(): string
	{
		return (string) $this->seconds;
	}

	public function add( string|int|Duration $add ): self
	{
		if( $add instanceof Duration )
		{
			$this->seconds += $add->seconds;
		}
		else if( is_numeric( $add ))
		{
			$this->seconds += (float) $add;
		}

		return $this;
	}
}
