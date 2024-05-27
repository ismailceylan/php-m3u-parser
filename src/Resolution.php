<?php

namespace Iceylan\M3uParser;

class Resolution
{
	public int $width = 0;
	public int $height = 0;

	public function __construct( string $resolution )
	{
		[ $this->width, $this->height ] = explode( 'x', $resolution );
	}

	public function __toString(): string
	{
		return $this->width . 'x' . $this->height;
	}

	public function getPName(): string
	{
		return $this->height . 'P';
	}

	public function getPixels(): int
	{
		return $this->width * $this->height;
	}
}
