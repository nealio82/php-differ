<?php

declare(strict_types=1);

namespace Test;

use Madsen\Diff\{Diff, Differ};

class LineDifferTest extends AbstractDifferTest
{
    protected function getDataFolder(): string
    {
        return 'line-data';
    }

    protected function diff(Differ $differ, string $a, string $b): Diff
    {
        return $differ->diffLines($a, $b);
    }
}
