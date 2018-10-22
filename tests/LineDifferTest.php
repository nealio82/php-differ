<?php
namespace Test;

use Goldcrest\Diff\Differ;

class LineDifferTest extends AbstractDifferTest
{
    protected function getDataFolder()
    {
        return 'line-data';
    }

    protected function diff(Differ $differ, $a, $b)
    {
        return $differ->diffLines($a, $b);
    }
}
