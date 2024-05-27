<?php

namespace Iceylan\M3uParser;

class FrameRate
{
	public float $fps = 0.0;

	public function __construct( string $fps )
	{
		$this->fps = (float) $fps;
	}
}
