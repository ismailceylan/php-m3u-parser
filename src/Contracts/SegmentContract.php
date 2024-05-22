<?php

namespace Iceylan\PhpStreamDownloader\Contracts;

interface SegmentContract
{
	public static function test( string $line, int $lineNumber ): bool;
}
