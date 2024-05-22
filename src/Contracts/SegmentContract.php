<?php

namespace Iceylan\M3uParser\Contracts;

interface SegmentContract
{
	public static function test( string $line, int $lineNumber ): bool;
}
