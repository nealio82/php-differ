<?php

declare(strict_types=1);

namespace Test;

use Madsen\Diff\{Diff, Differ};

class WordDifferTest extends AbstractDifferTest
{
    protected function getDataFolder(): string
    {
        return 'word-data';
    }

    protected function diff(Differ $differ, string $a, string $b): Diff
    {
        return $differ->diffWords($a, $b);
    }
}
